<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Stripe\Customer;
use Stripe\Stripe;

class Card extends Model
{
    use HasFactory;

    public static function getCard(): ?array
    {
        Stripe::setApiKey(config('app.stripe_secret_key'));

        $card = null;
        $user = Auth::user();
        if ($user->stripe_id) {
            $customer = Customer::retrieve($user->stripe_id);
            $defaultCardId = $customer->default_source;
            if ($defaultCardId) {
                $defaultCard = $customer->retrieveSource(
                    $user->stripe_id,
                    $defaultCardId,
                    []
                );

                $card = [
                    'number' => str_repeat('*', 12) . $defaultCard->last4,
                    'exp' => $defaultCard->exp_year . '/' . $defaultCard->exp_month,
                ];
            }
        }

        return $card;
    }

    public static function createCustomer($token)
    {
        Stripe::setApiKey(config('app.stripe_secret_key'));

        $customer = Customer::create([
            'source' => $token,
            'email' => Auth::user()->email,
        ]);

        $user = User::find(Auth::id());
        $user->stripe_id = $customer->id;
        $user->save();
    }

    public static function updateCustomer($token)
    {
        Stripe::setApiKey(config('app.stripe_secret_key'));

        $user = Auth::user();
        $customer = Customer::retrieve($user->stripe_id);
        $card = $customer->createSource(
            $user->stripe_id,
            ['source' => $token]
        );

        $customer->update(
            $user->stripe_id,
            ['default_source' => $card->id]
        );
    }

    public static function deleteCard()
    {
        Stripe::setApiKey(config('app.stripe_secret_key'));

        $user = Auth::user();
        $customer = Customer::retrieve($user->stripe_id);
        $defaultCardId = $customer->default_source;
        $customer->deleteSource(
            $user->stripe_id,
            $defaultCardId,
            []
        );
    }
}
