<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;

class UserController extends Controller
{
    public function userlist($total_number=10)
    {
    	$users = User::paginate($total_number);
        return view('admin.user.index', ['users' => $users]);
    	//dd(User::get()->toArray());

    }
    public function userVerifiedList($total_number=10)
    {
    	$users = User::where('status','completed')->paginate($total_number);
        return view('admin.user.index', ['users' => $users]);
    	//dd(User::get()->toArray());

    }
    public function userPendingList($total_number=10)
    {
    	$users = User::where('status','pending')->paginate($total_number);
        return view('admin.user.index', ['users' => $users]);
    	//dd(User::get()->toArray());

    }
    public function deleteUser($userId)
    {
    	User::find($userId)->delete();
    	return response()->json(['url' => url()->previous(),"code"=>200], 200);
    	
    }
}
