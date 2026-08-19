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
            'user_id' => auth()->id(),
        ]);

        Comment::create($insertData);

        return redirect()->route('items.show', ['item_id' => $item_id]);
    }
}
