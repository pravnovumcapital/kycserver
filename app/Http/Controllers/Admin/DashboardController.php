<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
//use App\User;

class DashboardController extends Controller
{
    public function index()
    {
    	$usersCount = User::count();
    	$kycVerifiedCount = User::where('status','completed')->count();
    	$kycPendingCount = User::where('status','pending')->count();
    	$data = ['userCount'=>$usersCount,'kycVerifiedCount'=>$kycVerifiedCount,'kycPendingCount'=>$kycPendingCount];
    	return view('admin.dashboard',compact('data'));
    }
}
