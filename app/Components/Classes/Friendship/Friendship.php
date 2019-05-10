<?php

namespace App\Components\Classes\Friendship;

use App\Connect;
use App\Exceptions\FriendshipException;
use App\FriendRequest;
use App\Owner;

class Friendship
{
    private $authOwner;

    private $friendOwner;

    private $status;

    private $request;

    private $match;

    const FRIENDS = 'friends';

    const FRIEND_REQUEST = 'friend_request';

    const MATCH_CLOSED = 'match_closed';

    const MATCH = 'match';

    const NOTHING = 'nothing';

    /**
     * Friendship constructor.
     * @param Owner $authOwner
     * @param $friendOwner
     * @throws FriendshipException
     */
    public function __construct (Owner $authOwner, $friendOwner)
    {
        $this->authOwner = $authOwner;

        if($friendOwner instanceof Owner){
            $this->friendOwner = $friendOwner;
        }elseif(is_int($friendOwner)){
            $this->friendOwner = Owner::query()->findOrFail($friendOwner);
        }else{
            throw new FriendshipException('Second argument for make instance must be integer or instance of Owner model');
        }

        $this->loadStatus();
    }

    private function loadStatus()
    {
        if($this->authOwner->id == $this->friendOwner->id)
            throw new FriendshipException('You cannot perform actions with yourself.');

        if($this->friendOwner->signup_step)
            throw new FriendshipException('Owner not found.', 404);

        $isFriend = $this->authOwner->pet->findFriend($this->friendOwner->pet->id);
        if($isFriend){
            $this->status = self::FRIENDS;
        }else{
            $request = FriendRequest::query()->where('accept', null)->where(function ($query) {
                return $query->where(function ($q) {
                    return $q->where('requesting_owner_id', $this->authOwner->id)->where('responding_owner_id', $this->friendOwner->id);
                })->orWhere(function ($q) {
                    return $q->where('responding_owner_id', $this->authOwner->id)->where('requesting_owner_id', $this->friendOwner->id);
                });
            })->first();
            if($request){
                $this->request = $request;
                $this->status = self::FRIEND_REQUEST;
            }else{
                $match = Connect::query()->where(function ($query) {
                    return $query->where('requesting_owner_id', $this->authOwner->id)->where('responding_owner_id', $this->friendOwner->id);
                })->orWhere(function ($query) {
                    return $query->where('responding_owner_id', $this->authOwner->id)->where('requesting_owner_id', $this->friendOwner->id);
                })->first();
                if($match && !$match->closed){
                    $this->match = $match;
                    $this->status = self::MATCH;
                }elseif($match && $match->closed){
                    $this->status = self::MATCH_CLOSED;
                }else{
                    $this->status = self::NOTHING;
                }
            }
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function isStatus($status)
    {
        return strtolower($this->status) == strtolower($status);
    }

    public function makeMatch($match)
    {
        if($this->status != self::NOTHING)
            $this->handleStatusError();

        if(array_search($match, Connect::MATCHES) === false)
            throw new FriendshipException('Not found match status');

        $this->match = Connect::query()->create([
            'requesting_owner_id' => $this->authOwner->id,
            'responding_owner_id' => $this->friendOwner->id,
            'matches' => $match
        ]);

        $this->status = self::MATCH;

        return $this;
    }

    public function confirmMatch($match)
    {
        if($this->status != self::MATCH)
            $this->handleStatusError();

        if(array_search($match, Connect::MATCHES) === false)
            throw new FriendshipException('Not found match status');

        if($this->match->closed)
            throw new FriendshipException('This match are closed');

        if($this->match->matches == Connect::MATCHES['blacklist'])
            throw new FriendshipException('This match in black list');

        if($this->match->requesting_owner_id == $this->authOwner->id)
            throw new FriendshipException('Match creator cannot be accept or decline match');

        $this->match->update(['matches' => $match]);

        return $this;
    }

    public function closeMatch()
    {
        if($this->status != self::MATCH)
            $this->handleStatusError();

        if($this->match->closed)
            throw new FriendshipException('Match already closed');

        if($this->match->matches == Connect::MATCHES['blacklist'])
            throw new FriendshipException('This user was declined.');

        if($this->match->matches != Connect::MATCHES['all_matches'])
            throw new FriendshipException('Cannot be close match');

        $this->match->update(['closed' => true]);

        $this->status = self::MATCH_CLOSED;

        return $this;
    }

    public function makeFriendRequest()
    {
        if($this->status != self::MATCH_CLOSED)
            $this->handleStatusError();

        if(!$this->match->closed)
            throw new FriendshipException('Match not closed');

        $this->request = FriendRequest::query()->create([
            'requesting_owner_id' => $this->authOwner->id,
            'responding_owner_id' => $this->friendOwner->id
        ]);

        return $this;
    }

    public function updateFriendRequest($accepted)
    {
        if($this->status != self::FRIEND_REQUEST)
            $this->handleStatusError();

        $this->request->update(['accept' => $accepted]);

        if($accepted){
            $this->authOwner->pet->makeFriend($this->friendOwner->pet->id);
            $this->status = self::FRIENDS;
        }else{
            $this->status = self::MATCH_CLOSED;
        }

        return $this;
    }

    public function getFriendRequest()
    {
        return $this->request;
    }

    public function getMatch()
    {
        return $this->match;
    }

    public function getAuthOwner()
    {
        return $this->authOwner;
    }

    public function getFriendOwner()
    {
        return $this->friendOwner;
    }

    private function handleStatusError()
    {
        $message = '';
        switch ($this->status){
            case self::FRIENDS:
                $message = 'Friend already exist';
                break;
            case self::MATCH_CLOSED:
                $message = 'Match already closed';
                break;
            case self::FRIEND_REQUEST:
                $message = 'Friend request is created';
                break;
            case self::MATCH:
                $message = 'Match exist and not closed';
                break;
            case self::NOTHING:
                $message = 'Find this user in connect';
                break;
        }

        throw new FriendshipException($message);
    }
}