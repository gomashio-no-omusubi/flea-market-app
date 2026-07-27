<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
                //いいねした商品だけが表示される
                $query = Item::whereHas('favorites', function ($q) {
                    $q->where('user_id', auth()->id());
                });
                //検索欄が空でない場合
                if (!empty($keyword)) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                }

                $items = $query->with('purchase')->latest()->get();
            } else {
                // 未認証の場合は何も表示されない
                $items = collect(); // 空のコレクションを返す
            }
        }
        //おすすめ
        else {
            if (auth()->check()) {
                //自分が出品した商品は表示されない
                $query = Item::where('user_id', '!=', auth()->id());
                //それ以外（出品していない人の表示）は表示されるのでそれの処理
            } else {
                $query = Item::query();
            }
            //検索欄が空でない場合
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

        // 💡【ここを追加】ログインしている場合、この商品をいいねしているか判定
        if (auth()->check()) {
            // 先ほどの toggle と同じ favoriteItems() リレーションを使って判定します
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
    public function store(Request $request)
    {
        // 2. 画像のアップロード処理
        if ($request->hasFile('img_url')) {
            // public/items フォルダに画像を保存し、そのパス（items/ファイル名.jpg）を取得
            $path = $request->file('img_url')->store('items', 'public');

            // データベースには 'storage/items/ファイル名.jpg' の形で保存する場合
            $imagePath = 'storage/' . $path;

            // ※もし一覧画面で asset('storage/' . $item->img_url) としているなら
            // $imagePath = $path; だけで大丈夫です（一覧画面の表示ロジックと合わせてください）
        } else {
            $imagePath = 'images/dummy.png'; // 画像がない場合の初期値
        }

        $item = Item::create([
            'name'         => $request->input('name'),
            'price'        => $request->input('price'),
            'brand'        => $request->input('brand'),
            'description'  => $request->input('description'),
            'img_url'      => $imagePath, // 👈 'images/dummy.png' から $imagePath に変更！
            'condition_id' => $request->input('condition_id'),
            'user_id'      => auth()->id(),
        ]);
        // 4. カテゴリー（多対多）の同期（既存の処理）
        $item->categories()->sync($request->categories);

        return redirect()->route('mypage.index')->with('success', '商品の出品が完了しました。');
    }
}
