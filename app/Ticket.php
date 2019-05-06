<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'reported_owner_id', 'status', 'report_reason'
    ];

    const STATUSES = [
        'new' => 1,
        'reported_user' => 2,
        'in_progress' => 3,
        'resolved' => 4
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function reportedOwner()
    {
        return $this->belongsTo(Owner::class, 'reported_owner_id', 'id');
    }

    public function supportChats()
    {
        return $this->hasMany(SupportChatRoom::class);
    }
}
