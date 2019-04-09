<?php

namespace App\Http\Controllers\Admin;

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
            ['username' => 'Peter Dark',
            'email'    => 'peter@gmail.com',
            'date'     => '02/03/2019',
            'time'     => '15:40:25',
            'ticket'   => 'Ticket First',
            'status'   => 'New']
        ];

        $tickets = collect($tickets);

        $datatables = DataTables::collection($tickets);
        

        return $datatables->make(true);
    }
}
