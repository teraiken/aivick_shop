<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShippingFeeRequest;
use App\Models\Area;
use App\Models\ShippingFee;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ShippingFeeController extends Controller
{
    /**
     * 新規送料の作成フォームを表示する。
     *
     * @param [type] $id
     * @return View
     */
    public function create($id): View
    {
        $area = Area::find($id);

        return view('admin.shippingFees.create', compact('area'));
    }

    /**
     * 新しく作成された送料をストレージに格納する。
     *
     * @param ShippingFeeRequest $request
     * @param [type] $id
     * @return RedirectResponse
     */
    public function store(ShippingFeeRequest $request, $id): RedirectResponse
    {
        $area = Area::find($id);

        if ($this->checkForOverlappingPeriodsSubstituteStartDate($area, $request)) {
            session()->flash('errorMessage', __('shippingFee.overlapping_periods'));
            return to_route('admin.areas.show', compact('area'));
        }

        ShippingFee::create([
            'area_id' => $area->id,
            'fee' => $request->fee,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return to_route('admin.areas.show', compact('area'));
    }

    /**
     * 指定された送料を編集するためのフォームを表示する。
     *
     * @param [type] $id
     * @return View
     */
    public function edit($id): View
    {
        $shippingFee = ShippingFee::find($id);

        $area = $shippingFee->area;

        return view('admin.shippingFees.edit', compact('area', 'shippingFee'));
    }

    /**
     * ストレージ内の指定された送料を更新する。
     *
     * @param ShippingFeeRequest $request
     * @param [type] $id
     * @return RedirectResponse
     */
    public function update(ShippingFeeRequest $request, $id): RedirectResponse
    {
        $updateShippingFee = ShippingFee::find($id);

        $area = $updateShippingFee->area;

        $latestShippingFee = $area->latestShippingFee;

        if ($updateShippingFee->id === $latestShippingFee->id) {
            if ($this->checkForOverlappingPeriodsSubstituteStartDate($area, $request, $updateShippingFee)) {
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
        $updateShippingFee->end_date = $request->end_date;
        $updateShippingFee->save();

        return to_route('admin.areas.show', compact('area'));
    }

    /**
     * 指定された送料をストレージから削除する。
     *
     * @param [type] $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $shippingFee = ShippingFee::find($id);

        $shippingFee->delete();

        $area = $shippingFee->area;

        return to_route('admin.areas.show', compact('area'));
    }

    /**
     * 適用開始日を代入し、期間の重複がないか確認する。
     *
     * @param Area $area
     * @param ShippingFeeRequest $request
     * @param ShippingFee|null $updateShippingFee
     * @return boolean
     */
    private function checkForOverlappingPeriodsSubstituteStartDate(Area $area, ShippingFeeRequest $request, ShippingFee $updateShippingFee = null): bool
    {
        $shippingFees = $area->shippingFees;

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

    /**
     * ２つの日付の間に入っているか確認する。
     *
     * @param Carbon $target_date
     * @param Carbon $start_date
     * @param Carbon $end_date
     * @return boolean
     */
    private function isBetween(Carbon $target_date, Carbon $start_date, Carbon $end_date): bool
    {
        return Carbon::parse($target_date)->between($start_date, $end_date);
    }
}
