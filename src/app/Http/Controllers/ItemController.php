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
        $query = Item::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if (auth()->check()) {
            $query->where('user_id', '!=', auth()->id());
        }
        $items = $query->get();
        $likedItems = collect();
        if (auth()->check()) {
            $likedItems = auth()->user()->likes()
                ->with('item')
                ->get()
                ->pluck('item');
            if ($request->filled('search')) {
                $likedItems = $likedItems->filter(function ($item) use ($request) {
                    return str_contains($item->name, $request->search);
                });
            }
        }
        $tab = $request->get('tab', 'recommend');
        return view('index', compact('items', 'likedItems', 'tab'));
    }

    public function show(Item $item)
    {
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
