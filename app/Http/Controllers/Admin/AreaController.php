<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AreaRequestForStore;
use App\Http\Requests\AreaRequestForUpdate;
use App\Models\Area;
use App\Models\ShippingFee;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $maxRecords = 5;

        $areas = Area::paginate($maxRecords);

        return view('admin.areas.index', compact('areas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.areas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AreaRequestForStore $request)
    {
        $area = new Area;

        $area->name = $request->name;
        $area->save();

        ShippingFee::create([
            'area_id' => $area->id,
            'fee' => $request->fee,
            'start_date' => $request->start_date,
        ]);

        return to_route('admin.areas.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $area = Area::find($id);

        $shippingFees = $area->shippingFees;

        return view('admin.areas.show', compact('area', 'shippingFees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AreaRequestForUpdate $request, $id)
    {
        $area = Area::find($id);

        $area->name = $request->name;
        $area->save();

        return to_route('admin.areas.show', compact('area'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $area = Area::find($id);

        $area->delete();

        return to_route('admin.areas.index');
    }
}
