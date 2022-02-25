<?php

namespace App\Http\Controllers;

use App\Models\User;

class RegisterController extends Controller
{
    public function create()
    {
        return view('register.create');
    }

    public function store()
    {
        //@@45_All validations at:
            // https://laravel.com/docs/8.x/validation
            // See all inputs by replacing below with "return request()->all();"
        $attributes = request()->validate([
            'name' => 'required|max:255',
            //@@47_05:07_[unique:users,username], make sure that username don't already exist in db.
            'username' => 'required|min:3|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:7|max:255',
        ]);

        //@@45 If validation fails, it redirect to the previous page, and won't reach lines below.

        //@@45 auto bycrypt used in User.php's setPasswordAttribute
            // Alternatively, you can just preceed with:
                // $attributes['password'] = bcrypt($attributes['password']);
        User::create($attributes);

        //@@48_01:04 if you want flash message accessible through session, do:
            // session()->flash('success', 'Your account has been created.');
            // "with" below is a shorthand
        return redirect('/')->with('success', 'Your account has been created.');
    }
}
