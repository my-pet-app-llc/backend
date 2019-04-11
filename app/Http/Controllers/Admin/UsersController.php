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

    public function data(Owner $owner)
    {
        $owners = $owner->ownersData();

        $datatables = DataTables::of($owners)
            ->filterColumn('fullname', function($query, $keyword) {
                $sql = "CONCAT(owners.first_name,' ',owners.last_name)  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->editColumn('created_at', function($owner) {
                return $owner->created_at->format('m/d/Y') . view('users._row', compact('owner'))->render();;
            })
            ->addColumn('location', function($owner) {
                return 'example location';
            })
            ->addColumn('status', function($owner) {
                return $owner->statusName . view('users._status', compact('owner'))->render();
            });

        $datatables->rawColumns(['created_at', 'status']);
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
