<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \Yajra\DataTables\Facades\DataTables;
use App\Owner;
use App\User;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.index');
    }

    public function data()
    {
        $owners = Owner::with('user');

        $datatables = DataTables::of($owners)
            ->editColumn('username', function($owner) {
                return $owner->fullName . view('users._row', compact('owner'))->render();
            })
            ->editColumn('created_at', function($owner) {
                return $owner->created_at->format('m/d/Y');
            })
            ->addColumn('location', function($owner) {
                return 'example location';
            })
            ->addColumn('status', function($owner) {
                return $owner->statusName . view('users._status', compact('owner'))->render();;
            });

        $datatables->rawColumns(['username', 'status']);
        return $datatables->make(true);
    }

    public function show(User $user) 
    {
        return view('users._info', compact('user'))->render();
    }

    public function userBan(User $user) 
    {
        $user->owner->status = Owner::STATUS['banned'];
        $user->owner->save();
    }
}
