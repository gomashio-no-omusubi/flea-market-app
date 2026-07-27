<?php

namespace App\Http\Controllers;

use App\Models\Item;

class FavoriteController extends Controller
{
    public function toggle($item_id)
    {
        $item = Item::findOrFail($item_id);

        auth()->user()->favoriteItems()->toggle($item->id);

        return redirect()->route('items.show',  ['item_id' => $item->id]);
    }
}
