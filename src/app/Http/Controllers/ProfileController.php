<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Item;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    //プロフィール画面
    public function index(Request $request)
    {
        $user = auth()->user();
        $user->load('profile');

        $page = $request->query('page', 'buy');
        $items = collect();

        // 購入した商品一覧の取得
        if ($page === 'buy') {
            $purchases = Purchase::where('user_id', $user->id)
                ->with('item')
                ->latest()
                ->get();

            // 購入データから紐づく商品（Item）だけを綺麗に抽出
            $items = $purchases->map(function ($purchase) {
                return $purchase->item;
            })->filter(); // 万が一の商品データ欠損（null）対策
        }
        // 出品した商品一覧（あとで実装するため、今は空のコレクション）
        elseif ($page === 'sell') {
            $items = Item::where('user_id', $user->id)
                ->with('purchase')
                ->latest()
                ->get();
        }

        $profile = $user->profile;

        return view('mypage.index', compact('user', 'page', 'items', 'profile'));
    }

    //プロフィール編集（プロフィールの変更入力画面）
    public function edit()
    {
        $user = auth()->user();
        $profile = $user->profile;

        return view('mypage.edit', compact('user', 'profile'));
    }

    //プロフィール編集（変更したプロフィールの更新処理）
    public function update(ProfileRequest $request)
    {
        $user = auth()->user();
        $user->update(['name' => $request->name]);

        $profileData = $request->only(['postcode', 'address', 'building']);

        if ($request->hasFile('img_url')) {
            if ($user->profile && $user->profile->img_url) {
                Storage::disk('public')->delete($user->profile->img_url);
            }
            $profileData['img_url'] = $request->file('img_url')->store('profile', 'public');
        }

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        return redirect()->route('mypage.index');
    }

    //プロフィール設定（初回ログイン時のプロフィール保存処理）
    public function store(ProfileRequest $request)
    {
        $user = auth()->user();
        $user->update(['name' => $request->name]);

        $profileData = $request->only(['postcode', 'address', 'building']);

        if ($request->hasFile('img_url')) {
            if ($user->profile && $user->profile->img_url) {
                Storage::disk('public')->delete($user->profile->img_url);
            }
            $profileData['img_url'] = $request->file('img_url')->store('profile', 'public');
        }

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        return redirect()->route('items.index');
    }
}
