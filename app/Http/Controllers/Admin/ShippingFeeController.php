<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShippingFeeRequest;
use App\Models\ShippingFee;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ShippingFeeController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shippingFee = ShippingFee::find($id);

        return view('admin.shippingFees.edit', compact('shippingFee'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ShippingFeeRequest $request, $id)
    {
        $shippingFee = ShippingFee::find($id);

        DB::transaction(function () use ($request, $shippingFee) {
            $shippingFee->end_date = Carbon::parse($request->end_date)->endOfDay();
            $shippingFee->save();

            ShippingFee::create([
                'area_id' => $shippingFee->area->id,
                'fee' => $request->fee,
                'start_date' => Carbon::parse($request->end_date)->addDay(),
            ]);
        });

        $area = $shippingFee->area;

        return to_route('admin.areas.show', compact('area'));
    }
}
