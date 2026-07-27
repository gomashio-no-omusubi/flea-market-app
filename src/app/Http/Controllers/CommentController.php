<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $item_id)
    {
        $insertData = array_merge($request->validated(), [
            'item_id' => $item_id,
            //今ログインしているユーザーのIDを使うが、もし万が一ログインしていなかったら（nullだったら）、代わりに「1番のユーザー」として登録
            'user_id' => auth()->id(),
        ]);

        Comment::create($insertData);

        return redirect()->route('items.show', ['item_id' => $item_id]);
    }
}
