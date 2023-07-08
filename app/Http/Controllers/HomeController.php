<?php

namespace App\Http\Controllers;

use App\Classes\Settings;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    protected $settings;

    public function __construct(Settings $_settings)
    {

        $this->settings = $_settings;
    }


    public function index(){
        if(auth()->check())   return redirect()->route('dashboard');
        return view('login');
    }

    public function process_login(Request $request){
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->except(['_token']);

        if (auth()->attempt($credentials)) {

            return redirect()->route('dashboard');

        }else{
            session()->flash('message', 'Invalid Username or Password, Please check and try again');
            return redirect()->back();
        }
    }


    public function logout(){
        auth()->logout();
        return redirect()->route('home');
    }


    public function myprofile(Request $request)
    {
        if(!auth()->check())   return redirect()->route('home');

        $data['title'] = "My Profile";

        $data['user'] = auth()->user();

        if($request->method() == "POST")
        {
            $user = $request->only(User::$profile_fields);

            if(!empty($user['password']))
            {
                $user['password'] = bcrypt($user['password']);
            }else
            {
                unset($user['password']);
            }

            $data['user']->update($user);

            return redirect()->route('myprofile')->with('success','Profile has been updated successfully!');
        }

        return setPageContent('myprofile',$data);
    }

}
