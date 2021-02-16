<?php

namespace App\Http\Controllers\V1\User;

use App\Enums\RoleEnum;
use App\Enums\UserStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\DinkesInviteRequest;
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

class DinkesController extends Controller
{
    public function index(Request $request)
    {
        $order           = $request->input('order', 'nama');
        $order_direction = $request->input('order_direction', 'asc');
        $perpage         = $request->input('perpage', 20);

        $model = User::byRoleId(RoleEnum::DINKES()->getIndex())
                        ->leftJoin('lab_satelit', 'users.lab_satelit_id', 'lab_satelit.id');

        $model->orderBy('users.nama', $order_direction);

        return UserDinkesResource::collection($model->paginate($perpage));
    }


    public function invite(DinkesInviteRequest $request)
    {
        DB::beginTransaction();
        try {
            $invite = Invite::create([
                'token' => Uuid::uuid4(),
                'email' => $request->input('email'),
            ]);
            $user = User::create($request->validated() + [
                'status' => UserStatusEnum::INACTIVE(),
                'invited_at' => Carbon::now(),
            ]);
            $url = config('app.url') . '/registration-dinkes/' . $invite->token;
            $user->notify(new InviteNotification($url));
            DB::commit();
            return response()->json(["message" => "Undangan terkirim"]);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function register(DinkesInviteRequest $request)
    {
        $invite = Invite::where('token', $request->token)->first();
        $invite->user()->update([
            'password' => Hash::make($request->password),
            'status' => UserStatusEnum::ACTIVE(),
            'register_at' => Carbon::now()
        ]);
        $invite->delete();
        return response()->json(["message" => "Registrasi Berhasil"]);
    }

    public function update(User $user, Request $request)
    {
        $request->validate([
            'kota_id' => 'required|exists:kota,id'
        ]);
        $user->update($request->only('kota_id'));
        return $user;
    }
}
