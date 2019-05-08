<?php

namespace App\Components\Traits\Models;

use App\Connect;
use App\Owner;
use Carbon\Carbon;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Predis\Client;

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
            ->whereNotIn('id', $existingIds)
            ->where('id', '<>', $thisOwnerId)
            ->where('signup_step', 0)
            ->where('status', '<>', self::STATUS['banned'])
            ->whereDate('location_updated_at', '>', Carbon::now()->subHours(24)->format('Y-m-d H:i:s'))
            ->distance(
                'location_point',
                (new Point($this->location_point->getLat(), $this->location_point->getLng())),
                self::PRE_RADIUS/self::DISTANCE_IN_MILE,
                false
            )
            ->with('pet.pictures')
            ->get();

        if($matches->count()){
            $geoadd = ['GEOADD', 'locs'];
            $zrem = ['ZREM', 'locs'];
            foreach ($matches as $match) {
                $geoadd[] = $match->location_point->getLng();
                $geoadd[] = $match->location_point->getLat();
                $geoadd[] = (string)$match->id;
                $zrem[] = (string)$match->id;
            }
            $georadius = ['GEORADIUS', 'locs', $this->location_point->getLng(), $this->location_point->getLat(), self::RADIUS, 'mi'];

            $redis = new Client();
            try{
                $redis->connect();
            }catch(\Exception $e){
                return $matches->first();
            }
            $redis->executeRaw($geoadd);
            $inRadius = $redis->executeRaw($georadius);
            $redis->executeRaw($zrem);

            $match = $inRadius ? $matches->where('id', $inRadius[0])->first() : null;
        }else{
            $match = null;
        }

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
            ->orWhere('responding_owner_id', $id);
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

    private function getStaticQuery ()
    {
        return static::query();
    }
}