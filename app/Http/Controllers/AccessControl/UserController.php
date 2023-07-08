<?php

namespace App\Http\Controllers\AccessControl;

use App\Models\Warehousestore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Models\Group;
use App\Models\User;
use App\Http\Controllers\Controller;


class UserController extends Controller
{

    public function index()
    {
        if(auth()->user()->group_id == 1) {
            $users = User::all();
        }else{
            $users = User::where('group_id', '>', 1)->get();
        }
        $data['title'] = "System Users";
        $data['users'] = $users;
        $data['depts'] = config('app.departments.'.config('app.store'));
        return setPageContent('user.list-users', $data);
    }


    public function create()
    {
        $data['title'] = "Add new System User";
        $data['groups'] = Group::where('status', '1')->get(['id', 'name']);
        $data['user'] = new User();
        $data['departments'] = Warehousestore::select('id', 'name')->get();
        $data['depts'] = config('app.departments.'.config('app.store'));
        return setPageContent('user.add-user', $data);
    }


    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, User::$rules);

        if ($validator->fails()) {
            if ($request->ajax()) {
                $res['msg'] = $validator->errors()->toJson();
                return response()->json($res);
            }
            $request->flash();
            return back()->withInput()->withErrors($validator);
        }

        $data['status'] = 1;



        $res['status'] = false;
        $res['msgtype'] = 'error';
        $res['msg'] = "There was an error creating User";

        $userdata = $data;
        unset($userdata['_token']);

        $userdata['password'] = bcrypt( $userdata['password']);

        DB::transaction(function () use ($data, $userdata, &$res){
            $new_user = new User();
            $new_user = $new_user->updateOrCreate($userdata);
        });

        if ($request->ajax()) {
            return response()->json($res);
        } else {
            return redirect()->route('user.create')->with('success', 'User updated successfully!');

        }
    }


    public function edit($id)
    {
        $data['title'] = "Edit System User";
        $data['groups'] = Group::where('status', '1')->get(['id', 'name']);
        $data['user'] = User::find($id);
        $data['departments'] = Warehousestore::select('id', 'name')->get();
        return view('user.add-user', $data);
    }



    public function update(Request $request, $id)
    {
        $res['status'] = false;
        $res['msgtype'] = 'error';
        $res['msg'] = "There was an error updating User";

        $data = $request->all();

        $validator = Validator::make($data, User::$rules);

        if ($validator->fails()) {
            if ($request->ajax()) {
                $res['msg'] = $validator->errors()->toJson();
                return response()->json($res);
            }
            $request->flash();
            return back()->withInput()->withErrors($validator);
        }

        $userdata = $data;
        unset($userdata['_token']);
        unset($userdata['_method']);

        if(empty($request->password)){
            unset($userdata['password']);
        }else{
            $userdata['password'] = bcrypt( $userdata['password']);
        }


        DB::transaction(function () use ($data, $id,$userdata, &$res) {
            $new_user = User::find($id);
            $new_user->update($userdata);
        });

        if ($request->ajax()) {
            return response()->json($res);
        } else {
            return redirect()->route('user.edit',[$id])->with('success', 'User updated successfully!');
        }

    }


    public function toggle($id)
    {
        $res = parent::toggleState(User::find($id));
        if ($res->status == 1)
            return redirect()->route('user.index')->with('success', "User activated successfully");
        else
            return redirect()->route('user.index')->with('error', "User deactivated successfully");
    }


}
