<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [];
        if (Auth::user()->role == 1) {
            $data['users'] = User::where('role', 2)->limit(10)->latest('created_at')->get();
            return view("admin.dashboard", $data);
        } else {
            $data['products'] = Product::with('tags', 'suppliers', 'categories')->get() ?? [];
            return view("user.dashboard", $data);
        }
    }
}
