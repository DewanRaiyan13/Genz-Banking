<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function dash()
    {
        // Your dashboard logic here
        return view('dashboard');
    }
    
    public function index()
{
        $user = Auth::user()->load('wallet');
        return view('dashboard', compact('user'));
}


}
