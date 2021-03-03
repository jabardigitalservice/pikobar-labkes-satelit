<?php

namespace App\Http\Controllers\V1\User;

use App\Enums\UserStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\DinkesInviteRequest;
use App\Http\Requests\DinkesRegisterRequest;
use App\Invite;
use App\Notifications\InviteNotification;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class DinkesController extends Controller
{
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

    public function register(DinkesRegisterRequest $request)
    {
        $invite = Invite::where('token', $request->token)->first();
        $invite->user()->update([
            'password' => Hash::make($request->input('password')),
            'status' => UserStatusEnum::ACTIVE(),
            'register_at' => Carbon::now()
        ]);
        $invite->delete();
        return response()->json(["message" => "Registrasi Berhasil"]);
    }

    public function update(User $user, Request $request)
    {
        $request->validate([
            'kota_id' => 'required|exists:labkes.kota,id'
        ]);
        $user->update($request->only('kota_id'));
        return $user;
    }
}
