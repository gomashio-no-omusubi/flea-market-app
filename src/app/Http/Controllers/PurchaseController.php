<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;

class PurchaseController extends Controller
{
    //商品の購入手続き（確認）画面
    public function purchaseShow($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        if (session()->has("changed_address_{$item_id}")) {
            $address = (object) session("changed_address_{$item_id}");
        } else {
            $user->load('profile');
            $address = $user->profile ?? null;
        }

        $paymentMethod = session("selected_payment_{$item_id}", "");

        return view('purchase.show', compact('item', 'user', 'address', 'paymentMethod'));
    }

    //商品購入（商品購入処理の実行）
    public function purchaseStore(PurchaseRequest $request, $item_id)
    {
        // 追加：プルダウン変更時などの送信であれば、選択された値をセッションに保存する
        if ($request->has('payment_method')) {
            session(["selected_payment_{$item_id}" => $request->payment_method]);
        }

        // 購入ボタンが押されていない（プルダウンの自動送信などの）場合、DB保存は絶対にせず、選んだ値を抱えたまま（withInput）画面をリロードする
        if ($request->input('submit_action') !== 'buy') {
            return back()->withInput();
        }

        $user = auth()->user();
        $paymentMethod = $request->payment_method;

        if (session()->has("changed_address_{$item_id}")) {
            $address = (object) session("changed_address_{$item_id}");
        } else {
            $user->load('profile');
            $address = $user->profile ?? null;
        }

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item_id,
            'payment_method' => $paymentMethod,
            'shipping_postcode' => $address->postcode,
            'shipping_address'  => $address->address,
            'shipping_building' => $address->building,
        ]);

        session()->forget("changed_address_{$item_id}");
        session()->forget("selected_payment_{$item_id}");

        return redirect()->route('items.index');
    }

    //送付先住所変更（送付先住所の変更入力画面）
    public function addressEdit($item_id)
    {
        $item = Item::findOrFail($item_id);

        return view('purchase.address.edit', compact('item', 'item_id'));
    }

    //送付先住所変更（変更した住所の保存処理）
    public function addressStore(AddressRequest $request, $item_id)
    {
        $validated = $request->validated();

        session([
            "changed_address_{$item_id}" => [
                'postcode' => $validated['postcode'],
                'address'  => $validated['address'],
                'building' => $validated['building'] ?? null,
            ]
        ]);

        return redirect()->route('purchase.show', ['item_id' => $item_id]);
    }
}
