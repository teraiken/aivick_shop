<?php

namespace App\Http\Controllers;

use App\Services\StripeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CardController extends Controller
{
    private StripeService $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * 指定されたリソースを表示する。
     *
     * @return View
     */
    public function show(): View
    {
        $card = $this->stripeService->getCard(Auth::user());

        return view('card.show', compact('card'));
    }

    /**
     * 新しく作成されたリソースをストレージに格納する。
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        if (Auth::user()->stripe_id) {
            $this->stripeService->updateCustomer($request->stripeToken, Auth::user());
        } else {
            $this->stripeService->createCustomer($request->stripeToken, Auth::user());
        }

        return to_route('card.show');
    }

    /**
     * ストレージ内の指定されたリソースを更新する。
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $this->stripeService->deleteCard(Auth::user());

        $this->stripeService->updateCustomer($request->stripeToken, Auth::user());

        return to_route('card.show');
    }

    /**
     * 指定されたリソースをストレージから削除する。
     *
     * @return RedirectResponse
     */
    public function destroy(): RedirectResponse
    {
        $this->stripeService->deleteCard(Auth::user());

        return to_route('card.show');
    }
}
