<?php

namespace App\Services;

use Stripe\Customer;
use Stripe\Stripe;

class StripeService
{
    private $stripe;

    public function __construct()
    {
        $this->stripe = Stripe::setApiKey(config('app.stripe_secret_key'));
    }

    public function getCard($user): ?array
    {
        $card = null;
        [$stripeId, $customer, $defaultCardId] = $this->getStripeInfo($user);
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
        [$stripeId, $customer, $defaultCardId] = $this->getStripeInfo($user);
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
        [$stripeId, $customer, $defaultCardId] = $this->getStripeInfo($user);
        $customer->deleteSource(
            $stripeId,
            $defaultCardId,
            []
        );
    }

    private function getStripeInfo($user)
    {
        $stripeId = $customer = $defaultCardId = null;
        $stripeId = $user->stripe_id;
        if (!$stripeId) {
            return array($stripeId, $customer, $defaultCardId);
        }

        $customer = Customer::retrieve($stripeId);
        $defaultCardId = $customer->default_source;

        return array($stripeId, $customer, $defaultCardId);
    }
}
