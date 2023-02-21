<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Http\Requests\AddressRequest;
use App\Models\Pref;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AddressController extends Controller
{
    /**
     * リソースの一覧を表示する。
     *
     * @return View
     */
    public function index(): View
    {
        $maxRecords = 5;

        $addresses = Address::whereUserId(Auth::id())->orderBy('id', 'desc')->paginate($maxRecords);

        return view('addresses.index', compact('addresses'));
    }

    /**
     * 新規リソースの作成フォームを表示する。
     *
     * @return View
     */
    public function create(): View
    {
        $prefs = Pref::all();

        return view('addresses.create', compact('prefs'));
    }

    /**
     * 新しく作成されたリソースをストレージに格納する。
     *
     * @param AddressRequest $request
     * @return RedirectResponse
     */
    public function store(AddressRequest $request): RedirectResponse
    {
        Address::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'postal_code' => $request->postal_code,
            'pref_id' => $request->pref_id,
            'address1' => $request->address1,
            'address2' => $request->address2,
            'phone_number' => $request->phone_number,
        ]);

        return to_route('addresses.index');
    }

    /**
     * 指定されたリソースを編集するためのフォームを表示する。
     *
     * @param [type] $id
     * @return View
     */
    public function edit($id): View
    {
        $address = Address::whereUserId(Auth::id())->find($id);
        $prefs = Pref::all();

        return view('addresses.edit', compact('address', 'prefs'));
    }

    /**
     * ストレージ内の指定されたリソースを更新する。
     *
     * @param AddressRequest $request
     * @param [type] $id
     * @return RedirectResponse
     */
    public function update(AddressRequest $request, $id): RedirectResponse
    {
        $address = Address::whereUserId(Auth::id())->find($id);

        $address->name = $request->name;
        $address->postal_code = $request->postal_code;
        $address->pref_id = $request->pref_id;
        $address->address1 = $request->address1;
        $address->address2 = $request->address2;
        $address->phone_number = $request->phone_number;
        $address->save();

        return to_route('addresses.index');
    }

    /**
     * 指定されたリソースをストレージから削除する。
     *
     * @param [type] $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $address = Address::whereUserId(Auth::id())->find($id);

        $address->delete();

        return to_route('addresses.index');
    }
}
