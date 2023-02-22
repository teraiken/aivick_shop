<?php

namespace App\Services;

use App\Models\User;
use Stripe\Customer;
use Stripe\Stripe;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('app.stripe_secret_key'));
    }

    /**
     * カード情報を取得する。
     *
     * @param User $user
     * @return array|null
     */
    public function getCard(User $user): ?array
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

    /**
     * Customerオブジェクトを作成する。
     *
     * @param string $token
     * @param User $user
     * @return void
     */
    public function createCustomer(string $token, User $user): void
    {
        $customer = Customer::create([
            'source' => $token,
            'email' => $user->email,
        ]);

        $user->stripe_id = $customer->id;
        $user->save();
    }

    /**
     * Customerオブジェクトを更新する。
     *
     * @param string $token
     * @param User $user
     * @return void
     */
    public function updateCustomer(string $token, User $user): void
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

    /**
     * カード情報を削除する。
     *
     * @param User $user
     * @return void
     */
    public function deleteCard(User $user): void
    {
        [$stripeId, $customer, $defaultCardId] = $this->getStripeInfo($user->stripe_id);
        $customer->deleteSource(
            $stripeId,
            $defaultCardId,
            []
        );
    }

    /**
     * Customerオブジェクト及びそのデフォルトのカードIDを取得する。
     *
     * @param string $stripeId
     * @return array
     */
    private function getStripeInfo(string $stripeId): array
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
