<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Invite;
use App\Models\Validator as ModelsValidator;
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

        $params = $request->get('params', false);
        $search = $request->get('search', false);
        $order  = $request->get('order', 'updated_at');

        if ($params) {
        }

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

        $models = $models->skip(($page - 1) * $perpage)->take($perpage)->get();

        return response()->json([
            'data' => $models,
            'count' => $count
        ]);
    }

    public function invite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email'
        ]);
        $validator->after(function ($validator) use ($request) {
            if (Invite::where('email', $request->input('email'))->exists()) {
                $validator->errors()->add('email', 'There exists an invite with this email!');
            }
        })->validate();
        DB::beginTransaction();
        try {
            do {
                $token = Str::random(20);
            } while (Invite::where('token', $token)->first());

            Invite::create([
                'token' => $token,
                'email' => $request->input('email'),
                'lab_satelit_id' => $request->lab_satelit_id,
            ]);

            User::create([
                'email' => $request->input('email'),
                'invited_at' => Carbon::now(),
                'role_id' => 8
            ]);

            $url = config('app.url') . '/registration/' . $token;

            Notification::route('mail', $request->input('email'))->notify(new InviteNotification($url));
            DB::commit();
            return response()->json(["message" => "Undangan terkirim", 'status' => 200], 200);
        } catch (Exception $e) {
            DB::rollback();
            return $e;
        }
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
            'lab_satelit_id' => $request->lab_satelit_id,
            'register_at' => Carbon::now(),
            'role_id' => 8
        ]);

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
