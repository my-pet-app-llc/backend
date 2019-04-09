<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\StoreUpdateRequest;
use App\Update;
use Illuminate\Support\Facades\Storage;
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
                return view('updates._remove', compact('update'))->render();
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
    public function store(StoreUpdateRequest $request, Update $update)
    {
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads', 'public');
            $update->image = $path;
        }

        $update->title = $request->title;
        $update->description = $request->description;
        $update->save();

        return redirect()->route('updates.index')->with('flash_message', __('admin.messages.update_save'));
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
     * @param  $update
     * @return \Illuminate\Http\Response
     */
    public function destroy(Update $update)
    {
        Storage::disk('public')->delete($update->image);
        
        $update->delete();
    }
}
