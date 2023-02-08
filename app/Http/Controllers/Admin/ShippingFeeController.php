<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShippingFeeRequest;
use App\Models\ShippingFee;
use Carbon\Carbon;

class ShippingFeeController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.shippingFees.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShippingFeeRequest $request)
    {
        $area = session('area');

        if ($this->checkForOverlappingPeriodsSubstituteStartDate($request)) {
            session()->flash('errorMessage', __('shippingFee.overlapping_periods'));
            return to_route('admin.areas.show', compact('area'));
        }

        $shippingFee = new ShippingFee();
        $shippingFee->area_id = $area['id'];
        $shippingFee->fee = $request->fee;
        $shippingFee->start_date = $request->start_date;
        if (is_null($request->end_date)) {
            $shippingFee->end_date = null;
        } else {
            $shippingFee->end_date = Carbon::parse($request->end_date)->endOfDay();
        }
        $shippingFee->save();

        return to_route('admin.areas.show', compact('area'));
    }

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
        $updateShippingFee = ShippingFee::find($id);

        $area = $updateShippingFee->area;

        $latestShippingFee = $area->latestShippingFee;

        if ($updateShippingFee->id === $latestShippingFee->id) {
            if ($this->checkForOverlappingPeriodsSubstituteStartDate($request, $updateShippingFee)) {
                session()->flash('errorMessage', __('shippingFee.overlapping_periods'));
                return to_route('admin.areas.show', compact('area'));
            }
        } else {
            if (is_null($request->end_date)) {
                session()->flash('errorMessage', __('shippingFee.end_date_none'));
                return to_route('admin.areas.show', compact('area'));
            } else {
                $shippingFees = $area->shippingFees;

                $overlapFlag = false;

                foreach ($shippingFees as $shippingFee) {
                    if ($shippingFee->id === $updateShippingFee->id) {
                        continue;
                    }
                    if ($this->isBetween($shippingFee->start_date, $request->start_date, $request->end_date)) {
                        $overlapFlag = true;
                        break;
                    }
                    if (is_null($shippingFee->end_date)) {
                        continue;
                    }
                    if ($this->isBetween($shippingFee->end_date, $request->start_date, $request->end_date)) {
                        $overlapFlag = true;
                        break;
                    }
                }

                if ($overlapFlag) {
                    session()->flash('errorMessage', __('shippingFee.overlapping_periods'));
                    return to_route('admin.areas.show', compact('area'));
                }
            }
        }

        $updateShippingFee->fee = $request->fee;
        $updateShippingFee->start_date = $request->start_date;
        if (is_null($request->end_date)) {
            $updateShippingFee->end_date = null;
        } else {
            $updateShippingFee->end_date = Carbon::parse($request->end_date)->endOfDay();
        }
        $updateShippingFee->save();

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
        $shippingFee = ShippingFee::find($id);

        $shippingFee->delete();

        $area = $shippingFee->area;

        return to_route('admin.areas.show', compact('area'));
    }

    private function checkForOverlappingPeriodsSubstituteStartDate($request, $updateShippingFee = null): bool
    {
        $shippingFees = session('area')->shippingFees;

        if (is_null($shippingFees)) {
            return false;
        }

        foreach ($shippingFees as $shippingFee) {
            if (!is_null($updateShippingFee) and $shippingFee->id === $updateShippingFee->id) {
                continue;
            }
            if ($this->isBetween($request->start_date, $shippingFee->start_date, $shippingFee->end_date)) {
                return true;
            }
        }

        return false;
    }

    private function isBetween($target_date, $start_date, $end_date): bool
    {
        return Carbon::parse($target_date)->between($start_date, $end_date);
    }
}
