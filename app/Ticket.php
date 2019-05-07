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

    public function getStatusNameAttribute($statusName)
    {
        return self::getStatusList()[$this->status];
    }

    public static function getStatusList()
    {
        return [
            self::STATUSES['in_progress'] => __('admin.tickets.statuses.in_progress'),
            self::STATUSES['reported_user']   => __('admin.tickets.statuses.reported_user'),
            self::STATUSES['resolved']  => __('admin.tickets.statuses.resolved'),
            self::STATUSES['new']     => __('admin.tickets.statuses.new'),
        ];
    }
}
