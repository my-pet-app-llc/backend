<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Update;
use App\Http\Controllers\Controller;
use \Yajra\DataTables\Facades\DataTables;

class UpdatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('updates.index');
    }

    public function data()
    {
        $updates = Update::select(['*']);

        $datatables = DataTables::of($updates)
            ->editColumn('title', function($update) {
                return $update->title;
            })
            ->editColumn('created_at', function($update) {
                return $update->created_at->format('m/d/Y');
            })
            ->addColumn('remove_btn', function($update) {
                return view('updates._remove')->render();
            });

        $datatables->rawColumns(['remove_btn']);
        return $datatables->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
