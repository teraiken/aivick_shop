<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Stripe\Customer;
use Stripe\Stripe;

class StripeService
{
    public function getCard(): ?array
    {
        Stripe::setApiKey(config('app.stripe_secret_key'));

        $card = null;
        [$stripeId, $customer, $defaultCardId] = $this->getStripeInfo();
        if (!$defaultCardId) {
            return $card;
        }

        $defaultCard = $customer->retrieveSource(
            $stripeId,
            $defaultCardId,
            []
        );

        $card = [
            'number' => str_repeat('*', 12) . $defaultCard->last4,
            'exp' => $defaultCard->exp_year . '/' . $defaultCard->exp_month,
        ];

        return $card;
    }

    public function createCustomer($token)
    {
        Stripe::setApiKey(config('app.stripe_secret_key'));

        $customer = Customer::create([
            'source' => $token,
            'email' => Auth::user()->email,
        ]);

        $user = Auth::user();
        $user->stripe_id = $customer->id;
        $user->save();
    }

    public function updateCustomer($token)
    {
        Stripe::setApiKey(config('app.stripe_secret_key'));

        [$stripeId, $customer, $defaultCardId] = $this->getStripeInfo();
        $card = $customer->createSource(
            $stripeId,
            ['source' => $token]
        );

        $customer->update(
            $stripeId,
            ['default_source' => $card->id]
        );
    }

    public function deleteCard()
    {
        Stripe::setApiKey(config('app.stripe_secret_key'));

        [$stripeId, $customer, $defaultCardId] = $this->getStripeInfo();
        $customer->deleteSource(
            $stripeId,
            $defaultCardId,
            []
        );
    }

    private function getStripeInfo()
    {
        $stripeId = $customer = $defaultCardId = null;
        $stripeId = Auth::user()->stripe_id;
        if (!$stripeId) {
            return array($stripeId, $customer, $defaultCardId);
        }

        $customer = Customer::retrieve($stripeId);
        $defaultCardId = $customer->default_source;

        return array($stripeId, $customer, $defaultCardId);
    }
}
