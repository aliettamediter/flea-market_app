<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;


class CommentController extends Controller
{
    public function store(CommentRequest $request, Item $item)
    {
        Comment::create([
            'item_id' => $item->id,
            'user_id' => auth()->id(),
            'body' => $request->content,
        ]);
        return back();
    }
}

