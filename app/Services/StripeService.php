<?php

namespace App\Services;

use Stripe\Customer;
use Stripe\Stripe;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('app.stripe_secret_key'));
    }

    public function getCard($user): ?array
    {
        $card = null;
        [$stripeId, $customer, $defaultCardId] = $this->getStripeInfo($user->stripe_id);
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

    public function createCustomer($token, $user)
    {
        $customer = Customer::create([
            'source' => $token,
            'email' => $user->email,
        ]);

        $user->stripe_id = $customer->id;
        $user->save();
    }

    public function updateCustomer($token, $user)
    {
        [$stripeId, $customer, $defaultCardId] = $this->getStripeInfo($user->stripe_id);
        $card = $customer->createSource(
            $stripeId,
            ['source' => $token]
        );

        $customer->update(
            $stripeId,
            ['default_source' => $card->id]
        );
    }

    public function deleteCard($user)
    {
        [$stripeId, $customer, $defaultCardId] = $this->getStripeInfo($user->stripe_id);
        $customer->deleteSource(
            $stripeId,
            $defaultCardId,
            []
        );
    }

    private function getStripeInfo($stripeId)
    {
        $customer = $defaultCardId = null;
        if (!$stripeId) {
            return [$stripeId, $customer, $defaultCardId];
        }

        $customer = Customer::retrieve($stripeId);
        $defaultCardId = $customer->default_source;

        return [$stripeId, $customer, $defaultCardId];
    }
}
