<?php

namespace App\Http\Controllers;

use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    private StripeService $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Display the specified resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $card = $this->stripeService->getCard(Auth::user());

        return view('card.show', compact('card'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->stripe_id) {
            $this->stripeService->updateCustomer($request->stripeToken, Auth::user());
        } else {
            $this->stripeService->createCustomer($request->stripeToken, Auth::user());
        }

        return to_route('card.show');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->stripeService->deleteCard(Auth::user());

        $this->stripeService->updateCustomer($request->stripeToken, Auth::user());

        return to_route('card.show');
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $this->stripeService->deleteCard(Auth::user());

        return to_route('card.show');
    }
}
