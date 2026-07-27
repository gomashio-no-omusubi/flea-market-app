<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\Address;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;

class PurchaseController extends Controller
{
    //商品の購入手続き（確認）画面
    public function purchaseShow($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        $temporaryAddress = Address::where([
            'user_id' => $user->id,
            'item_id' => $item_id
        ])->first();

        if ($temporaryAddress) {
            $address = $temporaryAddress;
        } else {
            //Userモデルにある profile() というリレーション関数を引っ張たあとに、$user->profileが使用できる
            $user->load('profile');
            //何かしらの原因でアクセスできなかった（未定義だった）場合、エラーにして画面を壊すのではなく、安全に null（空っぽ）という果にして $address に代入
            $address = $user->profile ?? null;
        }

        return view('purchase.show', compact('item', 'user', 'address'));
    }

    //商品購入（商品購入処理の実行）
    public function purchaseStore(PurchaseRequest $request, $item_id)
    {
        // 購入ボタンが押されていない（プルダウンの自動送信などの）場合、DB保存は絶対にせず、選んだ値を抱えたまま（withInput）画面をリロードする
        if ($request->input('submit_action') !== 'buy') {
            return back()->withInput();
        }

        //ブレイド画面のformタグで['item_id' => $item->id]を記述しitem_idの取り出しを担っているので、$item = Item::findOrFail($item_id);は不要
        $user = auth()->user();
        $paymentMethod = $request->payment_method;

        $temporaryAddress = Address::where([
            'user_id' => $user->id,
            'item_id' => $item_id
        ])->first();

        if ($temporaryAddress) {
            $address = $temporaryAddress;
        } else {
            $user->load('profile');
            $address = $user->profile ?? null;
        }

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item_id,
            'shipping_postcode' => $address->postcode,
            'shipping_address'  => $address->address,
            'shipping_building' => $address->building,
            'payment_method' => $paymentMethod,
        ]);

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

        Address::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'item_id' => $item_id,
            ],
            [
                'postcode' => $validated['postcode'],
                'address' => $validated['address'],
                'building' => $validated['building'] ?? null,
            ]
        );

        return redirect()->route('purchase.show', ['item_id' => $item_id]);
    }
}
