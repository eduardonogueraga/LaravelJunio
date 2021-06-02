<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\User;
use App\Profession;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = User::first(); //$user = auth()->user();

        return view('profile.edit', [
            'user' => $user,
            'professions' => Profession::orderBy('title')->get(),
        ]);
    }

    public function update(UpdateProfileRequest $request, User $user)
    {
        //$user = User::first(); //$user = auth()->user();
        $request->updateProfile($user);
         return redirect(route('profile.edit'));
        //return back();
    }
}
