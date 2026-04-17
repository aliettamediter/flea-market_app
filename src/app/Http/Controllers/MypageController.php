<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;
        $page = $request->get('page', 'sell');

        $items = $user->items()->latest()->get();

        $purchases = $user->purchases()->with('item')->latest()->get();

        return view('mypage.mypage', compact('user', 'profile', 'items', 'purchases', 'page'));
    }
}