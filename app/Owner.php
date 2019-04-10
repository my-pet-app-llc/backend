<?php

namespace App;

use App\Components\Traits\Models\OwnerMatches;
use App\Components\Traits\Models\OwnerRequests;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\Model;

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
        'location_point', 'location_updated_at'
    ];

    protected $dates = ['location_updated_at'];

    const RADIUS = 20;

    const DISTANCE_IN_MILE = 69.0585729;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pet()
    {
        return $this->hasOne(Pet::class);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
