<?php

namespace App\Http\Controllers;

use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    /**
     * Display the specified resource.
     * 
     * @param  \App\Services\StripeService  $stripeService
     * @return \Illuminate\Http\Response
     */
    public function show(StripeService $stripeService)
    {
        $card = $stripeService->getCard();

        return view('card.show', compact('card'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Services\StripeService  $stripeService
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, StripeService $stripeService)
    {
        if (Auth::user()->stripe_id) {
            $stripeService->updateCustomer($request->stripeToken);
        } else {
            $stripeService->createCustomer($request->stripeToken);
        }

        return to_route('card.show');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Services\StripeService  $stripeService
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StripeService $stripeService)
    {
        $stripeService->deleteCard();

        $stripeService->updateCustomer($request->stripeToken);

        return to_route('card.show');
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param  \App\Services\StripeService  $stripeService
     * @return \Illuminate\Http\Response
     */
    public function destroy(StripeService $stripeService)
    {
        $stripeService->deleteCard();

        return to_route('card.show');
    }
}
