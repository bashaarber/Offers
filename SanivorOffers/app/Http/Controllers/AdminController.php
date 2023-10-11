<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function getAllUsers(Request $request)
    {

        $users = User::all();

        return view('admin.user')->with('users', $users);
    }
}
