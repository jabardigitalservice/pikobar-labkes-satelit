<?php

namespace App\Http\Controllers\V1\User;

use App\Enums\RoleEnum;
use App\Enums\UserStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\LabInviteRequest;
use App\Http\Requests\LabRegisterRequest;
use App\Invite;
use App\Notifications\InviteNotification;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class LabController extends Controller
{
    public function invite(LabInviteRequest $request)
    {
        DB::beginTransaction();
        try {
            $invite = Invite::create([
                'token' => Uuid::uuid4(),
                'email' => $request->input('email'),
            ]);
            $user = User::create($request->all() + [
                'role_id' => RoleEnum::LABORATORIUM()->getIndex(),
                'status' => UserStatusEnum::INACTIVE(),
                'invited_at' => now(),
            ]);
            $url = config('app.url') . '/registration/' . $invite->token;

            $user->notify(new InviteNotification($url));
            DB::commit();
            return response()->json(["message" => "Undangan terkirim"]);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function register(LabRegisterRequest $request)
    {
        $invite = Invite::where('token', $request->token)->first();
        $invite->user()->update($request->all() + [
            'status' => UserStatusEnum::ACTIVE(),
            'register_at' => now(),
        ]);
        $invite->delete();
        return response()->json(["message" => "Registrasi Berhasil"]);
    }

    public function update(User $user, Request $request)
    {
        $request->validate([
            'lab_satelit_id' => 'required|exists:lab_satelit,id'
        ]);
        $user->update($request->only('lab_satelit_id'));
        return $user;
    }
}
