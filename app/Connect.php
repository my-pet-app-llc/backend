<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Connect extends Model
{
    protected $fillable = [
        'requesting_owner_id', 'responding_owner_id', 'matches', 'closed'
    ];

    const MATCHES = [
        'blacklist' => 0,
        'request_match' => 1,
        'all_matches' => 2
    ];

    public function requestingOwner ()
    {
        return $this->belongsTo(Owner::class, 'requesting_owner_id', 'id');
    }

    public function respondingOwner ()
    {
        return $this->belongsTo(Owner::class, 'responding_owner_id', 'id');
    }
}
