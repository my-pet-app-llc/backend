<?php

namespace App\Http\Controllers\Admin;

use App\Ticket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \Yajra\DataTables\Facades\DataTables;
use App\Owner;
use App\User;
;

class TicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('tickets.index');
    }

    public function data()
    {
        $tickets = [
            [
                'id' => 1,
                'username' => 'Peter Dark',
                'email'    => 'peter@gmail.com',
                'date'     => '02/03/2019',
                'time'     => '15:40:25',
                'ticket'   => 'Ticket First',
                'status'   => 'New'
            ]
        ];

        $tickets = collect($tickets);

        $datatables = DataTables::collection($tickets);
        

        return $datatables->make(true);
    }

    public function show(Ticket $ticket)
    {
        $ticket->load('supportChats.owner.user');
        $creator_room = $ticket->supportChats->where('owner_id', $ticket->owner_id)->first();
        $reported_room = $ticket->supportChats->where('owner_id', $ticket->reported_owner_id)->first();

        return response([
            'is_report' => (bool)$ticket->reported_owner_id,
            'is_resolved' => ($ticket->status == Ticket::STATUSES['resolved']),
            'rooms' => [
                'creator_room' => [
                    'id' => $creator_room->id,
                    'email' => $creator_room->owner->user->email,
                    'full_name' => $creator_room->owner->fullName
                ],
                'reported_room' => $reported_room ? [
                        'id' => $reported_room->id,
                        'email' => $reported_room->owner->user->email,
                        'full_name' => $reported_room->owner->fullName
                    ] : null
            ]
        ]);
    }
}
