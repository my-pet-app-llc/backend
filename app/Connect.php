<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Connect extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'requesting_owner_id', 'responding_owner_id', 'matches', 'closed'
    ];

    protected $dates = ['deleted_at'];

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
