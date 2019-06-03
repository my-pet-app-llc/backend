<?php

namespace App;

use App\Components\Traits\Models\OwnerMatches;
use App\Components\Traits\Models\OwnerRequests;
use Carbon\Carbon;
use GeoJson\Geometry\Point;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Owner
 * @package App
 *
 * @property integer $id
 * @property integer $user_id
 * @property User $user
 * @property string $first_name
 * @property string $last_name
 * @property string $gender
 * @property integer $age
 * @property string $birthday
 * @property string $occupation
 * @property string $hobbies
 * @property string $pets_owned
 * @property string $profile_picture
 * @property string $favorite_park
 * @property integer $signup_step
 * @property Point $location_point
 * @property Carbon $location_updated_at
 * @property integer $utc
 * @property integer $status
 * @property Carbon $suspended_to
 * @property string $suspended_job_id
 *
 * @property Collection $reports
 * @property Collection $tickets
 */
class Owner extends Model
{
    use OwnerMatches, OwnerRequests, SpatialTrait;

    protected $spatialFields = [
        'location_point'
    ];

    protected $fillable = [
        'user_id', 'first_name', 'last_name',
        'gender', 'age', 'birthday',
        'occupation', 'hobbies', 'pets_owned',
        'profile_picture', 'favorite_park', 'signup_step',
        'location_point', 'location_updated_at', 'utc',
        'status', 'suspended_to', 'suspended_job_id'
    ];

    protected $dates = ['location_updated_at', 'suspended_to'];

    /**
     * Use this fields if when update owner status
     * to "suspended" you need send mail to owner
     *
     * @var Ticket|null
     */
    public $suspendedTicket = null;

    /**
     * Radius for owners found to connection
     *
     * @var integer
     */
    const RADIUS = 20;

    /**
     * Suspended user time in minutes
     *
     * @var integer
     */
    const SUSPENDED_TIME = 4320;

    const STATUS = [
        'in_progres' => 1,
        'reported'   => 2,
        'reporting'  => 3,
        'banned'     => 4,
        'normal'     => 5,
        'suspended'  => 6
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pet()
    {
        return $this->hasOne(Pet::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function reports()
    {
        return $this->hasMany(Ticket::class, 'reported_owner_id', 'id');
    }

    public function supportChatRooms()
    {
        return $this->hasMany(SupportChatRoom::class);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getStatusNameAttribute($statusName)
    {
        return self::getStatusList()[$this->status];
    }

    public static function getStatusList()
    {
        return [
            self::STATUS['in_progres'] => __('admin.users.state.in_progress'),
            self::STATUS['reported']   => __('admin.users.state.reported'),
            self::STATUS['reporting']  => __('admin.users.state.reporting'),
            self::STATUS['banned']     => __('admin.users.state.banned'),
            self::STATUS['suspended']     => __('admin.users.state.suspended'),
            self::STATUS['normal']     => __('admin.users.state.normal'),
        ];
    }

    public function scopeOwnersData($query)
    {
        return $query->select([
            'owners.id',
            'owners.user_id',
            DB::raw("CONCAT(owners.first_name,' ', owners.last_name) as fullname"),
            'owners.age',
            'owners.status',
            'owners.created_at',
        ])->with('user');
    }

    public function reloadStatus ($unban = false)
    {
        $status = $this->status;
        $updatingArray = [
            'status' => $status
        ];

        if(in_array($status, [self::STATUS['banned'], self::STATUS['suspended']])){
            if($unban){
                $updatingArray['status'] = $this->getUnbannedStatus();
                if($status == self::STATUS['suspended'])
                    $updatingArray += ['suspended_to' => null, 'suspended_job_id' => null];
            }
        }else{
            $updatingArray['status'] = $this->getUnbannedStatus();
        }

        if($status != $updatingArray['status'])
            $this->update($updatingArray);
    }

    protected function getUnbannedStatus ()
    {
        $hasReports = (boolean)$this->reports->where('status', '!=', Ticket::STATUSES['resolved'])->count();
        $hasTickets = (boolean)$this->tickets->where('status', '!=', Ticket::STATUSES['resolved'])->count();

        return ($hasReports && $hasTickets ?
            self::STATUS['reporting'] :
            ($hasReports ?
                self::STATUS['reported'] :
                ($hasTickets ?
                    self::STATUS['in_progres'] :
                    self::STATUS['normal']
                )
            )
        );
    }
}
