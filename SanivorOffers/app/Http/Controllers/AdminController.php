<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function getAllUsers(Request $request)
    {
        $query = $request->input('query');

        $users = User::where('username','like','%'.$query.'%')->orderBy('id', 'DESC')->paginate(10);
        $users->appends(['query' => $query]);

        return view('admin.index', compact('users', 'query'));

    }
}
