<?php

namespace App\Components\Traits\Models;

use App\Connect;
use App\Owner;
use Carbon\Carbon;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use mysql_xdevapi\Exception;
use Predis\Client;
use DB;

/**
 * Trait OwnerMatches
 * @package App\Components\Traits\Models
 *
 * @method static blackLists()
 * @method static inRequests()
 * @method static matches()
 * @method static findAll()
 * @method static withOwnerParse()
 */

trait OwnerMatches
{
    /**
     * @return HasMany
     */
    public function requestedMatches ()
    {
        return $this->hasMany(Connect::class, 'requesting_owner_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function respondedMatches ()
    {
        return $this->hasMany(Connect::class, 'responding_owner_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function newRequestedMatches ()
    {
        return $this->hasMany(Connect::class, 'requesting_owner_id', 'id')->where('matches', Connect::MATCHES['request_match']);
    }

    /**
     * @return HasMany
     */
    public function notRespondedMatches ()
    {
        return $this->hasMany(Connect::class, 'responding_owner_id', 'id')->where('matches', Connect::MATCHES['request_match']);
    }

    /**
     * @return Collection
     */
    public function blackList ()
    {
        return static::query()
            ->blackLists()
            ->findAll($this->id)
            ->withOwnerParse($this->id)
            ->where('owners.id', $this->id)
            ->get();
    }

    /**
     * @return Collection
     */
    public function inRequest ()
    {
        return static::query()
            ->inRequests()
            ->findAll($this->id)
            ->withOwnerParse($this->id)
            ->where('owners.id', $this->id)
            ->get();
    }

    /**
     * @return Collection
     */
    public function matched ()
    {
        return $this->getStaticQuery()
            ->matches()
            ->findAll($this->id)
            ->withOwnerParse($this->id)
            ->where('owners.id', $this->id)
            ->get();
    }

    /**
     * @return Collection
     */
    public function notClose ()
    {
        return $this->getStaticQuery()
            ->notClosed()
            ->findAll($this->id)
            ->withOwnerParse($this->id)
            ->where('owners.id', $this->id)
            ->get();
    }

    /**
     * @param $lat
     * @param $lng
     * @param array $additionalFields
     * @return $this
     */
    public function updateLocation ($lat, $lng, array $additionalFields = [])
    {
        $updateArray = [
                'location_point' => DB::raw("ST_PointFromText('POINT({$lng} {$lat})', 4326)"),
                'location_updated_at' => Carbon::now()
            ] + $additionalFields;

        $this->update($updateArray);
        return $this;
    }

    /**
     * @return Owner
     */
    public function findToConnect ()
    {
        $friends = $this->pet->friends()->with(['friend'])->get()->pluck('friend.owner_id');
        $connects = Connect::query()
            ->where('requesting_owner_id', $this->id)
            ->orWhere('responding_owner_id', $this->id)
            ->selectRaw('IF(connects.requesting_owner_id = ?, connects.responding_owner_id, connects.requesting_owner_id) as owner_id', [$this->id])
            ->pluck('owner_id');

        $existingIds = array_unique(array_merge($friends->toArray(), $connects->toArray()));
        $thisOwnerId = $this->id;

        $matches = $this->getStaticQuery()
            ->ownersInRadius($this->location_point->getLat(), $this->location_point->getLng(), self::RADIUS)
            ->whereNotIn('id', $existingIds)
            ->where('id', '<>', $thisOwnerId)
            ->where('signup_step', 0)
            ->whereNotIn('status', [self::STATUS['banned'], self::STATUS['suspended']])
            ->whereDate('location_updated_at', '>', Carbon::now()->subHours(24)->format('Y-m-d H:i:s'))
            ->with('pet.pictures')
            ->get();

        $match = $matches->count() ? Owner::query()->find($matches->first()->id) : null;

        return $match;
    }

    /**
     * @param Owner $check
     * @return bool
     */
    public function existMatch ($check)
    {
        $connects = Connect::query()
            ->where(function ($query) use ($check) {
                return $query->where('requesting_owner_id', $this->id)->where('responding_owner_id', $check->id);
            })
            ->orWhere(function ($query) use ($check) {
                return $query->where('responding_owner_id', $this->id)->where('requesting_owner_id', $check->id);
            })
            ->count();

        return (bool)$connects;
    }

    /**
     * @param Owner $owner
     * @return Connect
     */
    public function matchByUser ($owner)
    {
        $connect = Connect::query()
            ->where(function ($query) use ($owner) {
                return $query->where('requesting_owner_id', $this->id)->where('responding_owner_id', $owner->id);
            })
            ->orWhere(function ($query) use ($owner) {
                return $query->where('responding_owner_id', $this->id)->where('requesting_owner_id', $owner->id);
            })
            ->first();

        return $connect;
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeBlackLists ($query)
    {
        return $query->where('matches', Connect::MATCHES['blacklist']);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeInRequests ($query)
    {
        return $query->where('matches', Connect::MATCHES['request_match']);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeMatches ($query)
    {
        return $query->where('matches', Connect::MATCHES['all_matches']);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeNotClosed ($query)
    {
        return $query->where('closed', false)->where(function ($q) {
            return $q->where('matches', Connect::MATCHES['all_matches'])->orWhere('matches', Connect::MATCHES['request_match']);
        });
    }

    /**
     * @param Builder $query
     * @param integer $id
     * @return Builder
     */
    public function scopeFindAll ($query, $id)
    {
        return $query
            ->where('requesting_owner_id', $id)
            ->orWhere('responding_owner_id', $id)
            ->whereNull('deleted_at');
    }

    /**
     * @param Builder $query
     * @param $id
     * @return Builder
     */
    public function scopeWithOwnerParse ($query, $id)
    {
        return $query
            ->join('connects', function ($join) {
                return $join->on('connects.requesting_owner_id', '=', 'owners.id')->orOn('connects.responding_owner_id', '=', 'owners.id');
            })
            ->selectRaw('IF(connects.requesting_owner_id = ?, connects.responding_owner_id, connects.requesting_owner_id) as owner_id', [$id])
            ->selectRaw('IF(connects.requesting_owner_id = ?, 1, 0) as creator', [$id])
            ->selectRaw('connects.id')
            ->selectRaw('connects.created_at')
            ->selectRaw('connects.updated_at')
            ->selectRaw('connects.closed');
    }

    public function scopeOwnersInRadius ($query, $lat, $lng, int $radius, bool $millage = true)
    {
        $ml = 3659;
        $km = 6371;
        $faultMlDistance = 20;
        $faultKmDistance = 32.1869;
        $faultMl = 1.5;
        $faultKm = 1.60934;

        if($radius <= 0)
            throw new \Exception('Radius must be greater 0.');

        $fault = $radius / ($millage ? $faultMlDistance : $faultKmDistance) * ($millage ? $faultMl : $faultKm);

        $formatter = $millage ? $ml : $km;

        $radiusSelect = "({$formatter} * acos(cos(radians(?)) * cos(radians(st_y(`location_point`))) * cos(radians(st_x(`location_point`)) - radians(?)) + sin(radians(?)) * sin(radians(st_y(`location_point`))))) as distance";

        return $query->selectRaw($radiusSelect, [
            $lat, $lng, $lat
        ])->selectRaw('id')->having('distance', '<=', $radius - $fault);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getStaticQuery ()
    {
        return static::query();
    }
}