<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $items = Item::where('user_id', $user->id)->get();
        $purchases = Purchase::where('buyer_id', $user->id)->get();
        $page = $request->get('page', 'sell');
        return view('mypage.mypage', compact('user', 'items', 'purchases', 'page'));
    }

    // プロフィール編集フォーム
    public function edit()
    {
        $user = auth()->user();
        return view('mypage.profile', compact('user'));
    }

    // プロフィール更新
    public function update(ProfileRequest $request)
    {
        $user = auth()->user();

        $user->update([
            'name'        => $request->input('name'),
            'postal_code' => $request->input('postal_code'),
            'address'     => $request->input('address'),
            'building'    => $request->input('building'),
        ]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
            $user->update(['profile_image' => $path]);
        }

        return redirect('/');
    }
}