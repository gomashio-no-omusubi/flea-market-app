<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;

class ItemController extends Controller
{
    //商品一覧
    public function index(Request $request)
    {
        $tab = $request->query('tab');
        $keyword = $request->input('keyword');

        // マイリスト
        if ($tab === 'mylist') {
            if (auth()->check()) {
                $query = Item::whereHas('favorites', function ($q) {
                    $q->where('user_id', auth()->id());
                });

                if (!empty($keyword)) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                }

                $items = $query->with('purchase')->latest()->get();
            } else {
                $items = collect();
            }
        }
        //おすすめ
        else {
            if (auth()->check()) {
                $query = Item::where('user_id', '!=', auth()->id());
            } else {
                $query = Item::query();
            }
            if (!empty($keyword)) {
                $query->where('name', 'like', '%' . $keyword . '%');
            }

            $items = $query->with('purchase')->latest()->get();
        }
        return view('items.index', compact('items', 'keyword'));
    }

    //商品詳細
    public function show($item_id)
    {
        $item = Item::with(['user', 'favorites', 'comments.user', 'categories', 'condition'])->withCount('favorites')->findOrFail($item_id);

        if (auth()->check()) {
            $item->is_favorited_by_user = auth()->user()->favoriteItems()->where('item_id', $item->id)->exists();
        } else {
            $item->is_favorited_by_user = false;
        }

        return view('items.show', compact('item'));
    }

    //商品出品手続き（確認）画面
    public function create()
    {
        $categories = Category::all();
        $conditions = Condition::all();

        return view('items.create', compact('categories', 'conditions'));
    }

    //商品出品（商品出品処理の実行）
    public function store(ExhibitionRequest $request)
    {
        if ($request->hasFile('img_url')) {
            $path = $request->file('img_url')->store('items', 'public');

            $imagePath = 'storage/' . $path;
        } else {
            $imagePath = 'images/dummy.png';
        }

        $item = Item::create([
            'name'         => $request->input('name'),
            'price'        => $request->input('price'),
            'brand'        => $request->input('brand'),
            'description'  => $request->input('description'),
            'img_url'      => $imagePath,
            'condition_id' => $request->input('condition_id'),
            'user_id'      => auth()->id(),
        ]);

        $item->categories()->sync($request->categories);

        return redirect()->route('mypage.index')->with('success', '商品の出品が完了しました。');
    }
}
