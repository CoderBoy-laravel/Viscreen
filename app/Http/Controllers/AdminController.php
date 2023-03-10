<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use App\Models\User;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index () {
        $video = Upload::whereIn('type', array('video','bulkvideo'))->get();
        $audio = Upload::whereIn('type', array('audio','bulkaudio'))->get();
        $user = User::where('id', '!=', Auth::user()->id)->get();
        return view('Webpage.Admin.Dashboard', compact('video', 'audio', 'user'));
    }

    public function users() {
        $users = User::where('id', '!=', Auth::user()->id)->where('role', 'sub')->paginate(10);
        return view('Webpage.Admin.Users.Users', compact('users'));
    }
    public function deleteUser($id) {
        $users = User::where('id', $id)->delete();
        return back()->with('message', 'User Deleted Successfully');
    }

    public function addUser(Request $request) {
        if (Auth::user()->role == 'admin') {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'max:255'],
            ]);
            User::insert([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'created_at' => Carbon::now()
            ]);
            return back()->with('message', 'User Added Successfully');
        }
        else{
            return abort(403);
        }
    }

    public function settings(){
        $config = Setting::where('code','TIMELINE')->first();
        if (Auth::user()->role == 'admin') {
            return view('Webpage.Admin.Settings', compact('config'));
        }else{
            return abort(403);
        }
    }

    public function updateSetting(Request $request){
        $config = Setting::where('code','TIMELINE')->first();
        if(!$config){
            Setting::insert([
                'code'=>'TIMELINE',
                'data'=>$request->time
            ]);
        }else{
            Setting::where('code','TIMELINE')->update([
                'data'=>$request->time
            ]);
        }
        return back()->with('message', 'Update Successfully');
    }
}
