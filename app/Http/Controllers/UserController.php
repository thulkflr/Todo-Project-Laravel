<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('profile')->get();
        if ($users->isEmpty()) {
            return redirect()->route('users.index')->with('warning', 'There are no users in the database.');
        }
        foreach ($users as $user) {
            echo $user->name . '<br>';
         }
        return view('users.index', compact('users'))->with('success', 'All rights reserved.');
    }
    public function createProfile(Request $request){
        $user= $request->user();
        $profile= $user->profile()->updateOrCreate([
            'user_id'=>$user->id,
            'phone'=>$user->input('photo'),
            'address'=>$user->input('address'),
        ]);

        return redirect()->route('users.show',$user)->with('success','Profile created successfully');
    }

    public function show($id)
    {
        $user=User::with('profile')->findOrFail($id);
        return view('users.show',compact('user'));

    }
}
