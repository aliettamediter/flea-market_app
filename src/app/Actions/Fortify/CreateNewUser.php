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
        file_put_contents(storage_path('logs/test.log'), print_r($input, true));

        Validator::make(
            $input,
            (new RegisterRequest())->rules(),
            (new RegisterRequest())->messages()
        )->validate();

        $user = User::create([
            'email'    => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        $user->profile()->create([
            'name' => $input['name'],
        ]);

        return $user;
    }
}