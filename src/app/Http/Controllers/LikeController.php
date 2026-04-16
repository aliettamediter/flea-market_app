<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Item;

class LikeController extends Controller
{
    public function store(Item $item)
    {
        Like::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
        ]);

        return back();
    }
    public function destroy(Item $item)
    {
        Like::where('user_id', auth()->id())
            ->where('item_id', $item->id)
            ->delete();

        return back();
    }
}
