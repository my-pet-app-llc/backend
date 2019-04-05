<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \Yajra\DataTables\Facades\DataTables;
use App\Owner;

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
                return $owner->fullName;
            })
            ->editColumn('created_at', function($owner) {
                return $owner->created_at->format('m/d/Y');
            })
            ->addColumn('location', function($owner) {
                return 'example location';
            })
            ->addColumn('status', function($owner) {
                return 'normal';
            });

        return $datatables->make(true);
    }
}
