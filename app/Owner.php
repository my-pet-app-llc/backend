<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    protected $fillable = [
        'user_id', 'first_name', 'last_name',
        'gender', 'age', 'birthday',
        'occupation', 'hobbies', 'pets_owned',
        'city', 'state', 'profile_picture',
        'signup_step'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pet()
    {
        return $this->hasOne(Pet::class);
    }
}
