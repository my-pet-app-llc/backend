<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    protected $fillable = [
        'user_id', 'first_name', 'last_name',
        'gender', 'age', 'birthday',
        'occupation', 'hobbies', 'pets_owned',
        'profile_picture', 'favorite_park', 'signup_step'
    ];

    const STATUS = [
        'in_progres' => 1,
        'reported'   => 2,
        'reporting'  => 3,
        'banned'     => 4,
        'normal'     => 5,
    ];

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
            self::STATUS['normal']     => __('admin.users.state.normal'),
        ];
    }
}
