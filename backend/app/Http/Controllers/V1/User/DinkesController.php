<?php

namespace App\Http\Controllers\V1\User;

use App\Enums\RoleEnum;
use App\Enums\UserStatusEnum;
use App\Http\Controllers\Controller;
use App\Invite;
use App\Notifications\InviteNotification;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use App\Http\Resources\UserDinkes as UserDinkesResource;
use Illuminate\Support\Facades\Hash;
use Validator;

class DinkesController extends Controller
{
    public function index(Request $request)
    {
        $role_ids = [RoleEnum::DINKES()->getIndex(), RoleEnum::SUPERADMIN()->getIndex()];
        $model = User::query()->whereIn('role_id', $role_ids);
        $model = $model->leftJoin('lab_satelit', 'users.lab_satelit_id', 'lab_satelit.id');

        $order = $request->get('order');
        if ($order) {
            $order_direction = $request->get('order_direction', 'asc');
            switch (strtolower($order)) {
                case 'lab':
                    $order = 'nama';
                    $model = $model->orderBy('lab_satelit.' . $order, $order_direction);
                    break;
                default:
                    $model = $model->orderBy($order, $order_direction);
                    break;
            }
        }

        $page = $request->get('page', 1);
        $perpage = $request->get('perpage', 20);
        $count = $model->count();
        $data = UserDinkesResource::collection($model->skip(($page - 1) * $perpage)->take($perpage)->get());

        return compact('data', 'count');
    }

    public function invite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'lab_satelit_id' => 'required|exists:lab_satelit,id',
            'username' => 'required|unique:users,username',
            'name' => 'required',
        ])->validate();

        DB::beginTransaction();
        try {
            $invite = Invite::create([
                'token' => Uuid::uuid4(),
                'email' => $request->input('email'),
            ]);

            $user = User::create([
                'name' => $request->input('name'),
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'role_id' => RoleEnum::DINKES()->getIndex(),
                'lab_satelit_id' => $request->lab_satelit_id,
            ]);

            $user->status = UserStatusEnum::INACTIVE();
            $user->invited_at = Carbon::now();
            $user->save();
            $url = config('app.url') . '/registration-dinkes/' . $invite->token;

            $user->notify(new InviteNotification($url));
            DB::commit();
            return response()->json(["message" => "Undangan terkirim", 'status' => 200]);
        } catch (Exception $e) {
            DB::rollback();
            return $e;
        }
    }

    public function register(Request $request)
    {
        Validator::make($request->all(), [
            'token' => 'required',
            'password' => 'required|confirmed|min:6',
        ])->validate();

        $invite = Invite::where('token', $request->token)->first();
        if(!$invite) {
            return response()->json(['message' => __('auth.registration_cannot_be_completed')], 404);
        }

        $user = User::where(['email' => $invite->email])->first();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        $user->status = UserStatusEnum::ACTIVE();
        $user->register_at = Carbon::now();
        $user->save();

        $invite->delete();

        return $user;
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
