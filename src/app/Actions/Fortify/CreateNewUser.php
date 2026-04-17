<?php

namespace App\Actions\Fortify;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    public function create(array $input)
    {
        $registerRequest = new RegisterRequest();
        Validator::make(
            $input,
            $registerRequest->rules(),
            $registerRequest->messages()
        )->validate();

        $user = User::create([
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
        $user->profile()->create([
            'name' => $input['name'],
        ]);

        return $user;
    }
}
