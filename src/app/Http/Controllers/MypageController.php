<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->profile;

        return view('mypage.index', compact('user', 'profile'));
    }
}