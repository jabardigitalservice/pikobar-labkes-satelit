<?php

namespace App\Http\Controllers\V1;

use App\Enums\RoleEnum;
use App\Enums\UserStatusEnum;
use App\Http\Controllers\Controller;
use App\Invite;
use App\Notifications\InviteNotification;
use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Validator;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $models = User::query();
        $order  = $request->get('order', 'updated_at');
        $count = $models->count();
        $page = $request->get('page', 1);
        $perpage = $request->get('perpage', 20);

        if ($order) {
            $order_direction = $request->get('order_direction', 'desc');
            if (empty($order_direction)) $order_direction = 'desc';

            switch ($order) {
                default:
                    $models = $models->orderBy('updated_at', $order_direction);
                    break;
            }
        }

        $models = $models->with('lab_satelit');
        $models = $models->skip(($page - 1) * $perpage)->take($perpage)->get()->append('status_name');

        return response()->json([
            'data' => $models,
            'count' => $count
        ]);
    }

    public function tokenInfo(Invite $invite)
    {
        return $invite;
    }

    public function register(Request $request)
    {
        $user = User::where(['email' => $request->email])->first();
        Validator::make($request->all(), [
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'name' => 'required',
            'koordinator' => 'required',
            'lab_satelit_id' => 'required',
            'token' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);
            
        $invite = Invite::where('token', $request->token)->first();
        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'koordinator' => $request->koordinator,
            'password' => Hash::make($request->password),
        ]);
        
        $user->status = UserStatusEnum::ACTIVE();
        $user->register_at = Carbon::now();
        $user->save();

        $invite->delete();

        return $user;
    }

    public function delete(User $user)
    {
        return $user->delete();
    }

    public function show(User $user)
    {
        $user->load('lab_satelit');
        return response()->json(['data' => $user]);
    }

    public function update(User $user, Request $request)
    {
        $value = $request->only('lab_satelit_id');
        Validator::make($value, [
            'lab_satelit_id' => 'required|exists:lab_satelit,id'
        ])->validate();

        $user->update($value);
        return $user;
    }
}
