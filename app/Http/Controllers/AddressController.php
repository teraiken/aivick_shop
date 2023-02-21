<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use App\Http\Requests\AddressRequest;
use App\Models\Pref;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $maxRecords = 5;

        $addresses = Address::whereUserId(Auth::id())->orderBy('id', 'desc')->paginate($maxRecords);

        return view('addresses.index', compact('addresses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $prefs = Pref::all();

        return view('addresses.create', compact('prefs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddressRequest $request)
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $address = Address::whereUserId(Auth::id())->find($id);
        $prefs = Pref::all();

        return view('addresses.edit', compact('address', 'prefs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AddressRequest $request, $id)
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $address = Address::whereUserId(Auth::id())->find($id);

        $address->delete();

        return to_route('addresses.index');
    }
}
