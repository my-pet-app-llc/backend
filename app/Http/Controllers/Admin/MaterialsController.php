<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use \Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreMaterialRequest;
use App\Material;

class MaterialsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('materials.index');
    }

    public function data()
    {
        $materials = Material::select(['*']);

        $datatables = DataTables::of($materials)
            ->editColumn('title', function($material) {
                return $material->title;
            })
            ->editColumn('created_at', function($material) {
                return $material->created_at->format('m/d/Y');
            })
            ->addColumn('remove_btn', function($material) {
                return view('materials._remove', compact('material'))->render();
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
    public function store(StoreMaterialRequest $request, Material $material)
    {
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads', 'public');
            $material->image = $path;
        }

        $material->title = $request->title;
        $material->short_text = $request->short_text;
        $material->full_text = $request->full_text;
        $material->address = $request->get('address', null);
        $material->lat = $request->lat;
        $material->lng = $request->lng;
        $material->phone_number = $request->phone_number;
        $material->website = $request->website;
        $material->state = strtoupper($request->state);
        $material->is_ecommerce = $request->get('is_ecommerce', null) ?? false;
        $material->save();

        return redirect()->route('materials.index')->with('flash_message', __('admin.messages.material_save'));
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
     * @param  int  $material
     * @return \Illuminate\Http\Response
     */
    public function destroy(Material $material)
    {
        Storage::disk('public')->delete($material->image);
        
        $material->delete();
    }
}
