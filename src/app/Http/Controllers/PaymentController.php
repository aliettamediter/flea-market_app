<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    public function create(Item $item)
    {
        abort_if($item->user_id === auth()->id(), 403, '自身が出品した商品は購入できません');
        $user = auth()->user();
        $profile = $user->profile;
        return view('purchase.confirm', compact('user', 'item', 'profile'));
    }

    public function store(PurchaseRequest $request, Item $item)
    {
        abort_if($item->user_id === auth()->id(), 403, '自身が出品した商品は購入できません');
        Stripe::setApiKey(config('services.stripe.secret'));
        $paymentMethod = $request->payment_method;
        $paymentMethodType = $paymentMethod === 'credit_card' ? ['card'] : ['konbini'];
        $session = Session::create([
            'payment_method_types' => $paymentMethodType,
            'line_items' => [
                [
                    'price_data' => [
                        'currency'     => 'jpy',
                        'product_data' => ['name' => $item->name],
                        'unit_amount'  => $item->price,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode'        => 'payment',
            'success_url' => route('purchase.success', $item) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('purchase.create', $item),
        ]);
        session([
            'payment_method' => $paymentMethod,
            'item_id'        => $item->id,
            'postal_code'    => $request->postal_code,
            'address'        => $request->address,
            'building'       => $request->building,
        ]);

        return redirect($session->url);
    }

    public function success(Item $item)
    {
        \Log::info('purchase session', [
            'payment_method' => session('payment_method'),
            'postal_code'    => session('postal_code'),
            'address'        => session('address'),
            'building'       => session('building'),
        ]);
        Purchase::create([
            'item_id'        => $item->id,
            'buyer_id'       => auth()->id(),
            'amount'         => $item->price,
            'payment_method' => session('payment_method'),
            'status'         => 'completed',
            'paid_at'        => now(),
            'postal_code'    => session('postal_code'),
            'address'        => session('address'),
            'building'       => session('building'),
        ]);

        $item->update(['status' => 'sold']);

        return redirect()->route('items.index');
    }
    public function editAddress(Item $item)
    {
        abort_if($item->user_id === auth()->id(), 403, '自身が出品した商品は購入できません');
        $user = auth()->user();
        $profile = $user->profile;
        return view('purchase.address', compact('user', 'item', 'profile'));
    }

    public function updateAddress(AddressRequest $request, Item $item)
    {
        abort_if($item->user_id === auth()->id(), 403, '自身が出品した商品は購入できません');
        $user = auth()->user();
        $user->profile->update([
            'postal_code' => $request->postal_code,
            'address'     => $request->address,
            'building'    => $request->building,
        ]);

        return redirect()->route('purchase.create', $item);
    }
}
