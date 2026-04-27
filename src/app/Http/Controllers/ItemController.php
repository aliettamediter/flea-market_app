<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Http\Requests\ExhibitionRequest;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request->input('search');

        $items = Item::query()
            ->search($search)
            ->when($user, fn ($q) => $q->where('user_id', '!=', $user->id))
            ->get();

        $likedItems = $user
            ? Item::query()
                ->search($search)
                ->whereIn('id', $user->likes()->pluck('item_id'))
                ->get()
            : collect();

        $tab = $request->get('tab', 'recommend');

        return view('index', compact('items', 'likedItems', 'tab'));
    }

    public function show(Item $item)
    {
        $item->load([
            'categories',
            'condition',
            'likes',
            'comments.user.profile',
        ]);

        return view('items.show', compact('item'));
    }

    public function create()
    {
        $categories = Category::all();
        $conditions = Condition::all();
        return view('items.create', compact('categories', 'conditions'));
    }

    public function store(ExhibitionRequest $request)
    {
        $imagePath = $request->file('image')->store('items', 'public');
        $item = Item::create([
            'user_id'      => auth()->id(),
            'name'         => $request->name,
            'description'  => $request->description,
            'price'        => $request->price,
            'condition_id' => $request->condition_id,
            'image'        => $imagePath,
            'brand'        => $request->brand,
        ]);
        $item->categories()->attach($request->category_id);
        return redirect()->route('items.index');
    }
}
