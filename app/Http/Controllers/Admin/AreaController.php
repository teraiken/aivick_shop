<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AreaRequestForStore;
use App\Http\Requests\AreaRequestForUpdate;
use App\Models\Area;
use App\Models\ShippingFee;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AreaController extends Controller
{
    /**
     * リソースの一覧を表示する。
     *
     * @return View
     */
    public function index(): View
    {
        $maxRecords = 5;

        $areas = Area::paginate($maxRecords);

        return view('admin.areas.index', compact('areas'));
    }

    /**
     * 新規リソースの作成フォームを表示する。
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.areas.create');
    }

    /**
     * 新しく作成されたリソースをストレージに格納する。
     *
     * @param AreaRequestForStore $request
     * @return RedirectResponse
     */
    public function store(AreaRequestForStore $request): RedirectResponse
    {
        $area = new Area;

        $area->name = $request->name;
        $area->save();

        ShippingFee::create([
            'area_id' => $area->id,
            'fee' => $request->fee,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return to_route('admin.areas.index');
    }

    /**
     * 指定されたリソースを表示する。
     *
     * @param [type] $id
     * @return View
     */
    public function show($id): View
    {
        $area = Area::find($id);

        $shippingFees = $area->shippingFees;

        return view('admin.areas.show', compact('area', 'shippingFees'));
    }

    /**
     * ストレージ内の指定されたリソースを更新する。
     *
     * @param AreaRequestForUpdate $request
     * @param [type] $id
     * @return RedirectResponse
     */
    public function update(AreaRequestForUpdate $request, $id): RedirectResponse
    {
        $area = Area::find($id);

        $area->name = $request->name;
        $area->save();

        return to_route('admin.areas.show', compact('area'));
    }

    /**
     * 指定されたリソースをストレージから削除する。
     *
     * @param [type] $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $area = Area::find($id);

        $area->delete();

        return to_route('admin.areas.index');
    }
}
