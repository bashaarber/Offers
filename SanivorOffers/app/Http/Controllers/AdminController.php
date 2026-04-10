<?php

namespace App\Http\Controllers;

use App\Models\User;

class AdminController extends Controller
{
    public function getAllUsers()
    {
        $users = User::orderBy('id', 'DESC')->paginate(50);

        return view('admin.index', compact('users'));
    }
}
