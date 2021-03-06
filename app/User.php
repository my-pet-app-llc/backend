<?php

namespace App;

use App\Exceptions\NotOwnerException;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'facebook_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['deleted_at'];

    /**
     * @return HasOne
     */
    public function owner()
    {
        return $this->hasOne(Owner::class);
    }

    /**
     * @return HasOneThrough
     */
    public function pet()
    {
        return $this->hasOneThrough(Pet::class, Owner::class);
    }

    /**
     * Generate auth user access token
     *
     * @return string
     * @throws NotOwnerException
     */
    public function apiLogin()
    {
        if(!$this->isPetOwner())
            throw new NotOwnerException();

        if($this->isBannedOwner())
            throw new NotOwnerException('You were banned.');

        $this->tokens()->update(['revoked' => true]);

        $personalAccessClient = env('APP_PERSONAL_ACCESS_CLIENT', 'application');
        $accessToken = $this->createToken($personalAccessClient)->accessToken;

        return $accessToken;
    }

    /**
     * @return bool
     */
    public function isPetOwner()
    {
        return (bool)$this->owner;
    }

    /**
     * @return bool
     */
    public function isBannedOwner()
    {
        return ($this->owner->status == Owner::STATUS['banned']);
    }

    /**
     * @return bool
     */
    public function isFacebookUser()
    {
        return (bool)$this->facebook_id;
    }
    
}
