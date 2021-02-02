<?php

namespace App\Http\Controllers\V1;

use App\Enums\RoleEnum;
use App\Enums\UserStatusEnum;
use App\Http\Controllers\Controller;
use App\Invite;
use App\Notifications\InviteNotification;
use App\User;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Validator;

class UserInvitationController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'lab_satelit_id' => 'required|exists:lab_satelit,id',
        ])->validate();

        DB::beginTransaction();
        try {
            $invite = Invite::create([
                'token' => Uuid::uuid4(),
                'email' => $request->input('email'),
            ]);
                
            $user = User::create([
                'email' => $request->input('email'),
                'role_id' => RoleEnum::LABORATORIUM()->getIndex(),
                'lab_satelit_id' => $request->lab_satelit_id,
            ]);

            $user->status = UserStatusEnum::INACTIVE();
            $user->invited_at = Carbon::now();
            $user->save();
            $url = config('app.url') . '/registration/' . $invite->token;

            $user->notify(new InviteNotification($url));
            DB::commit();
            return response()->json(["message" => "Undangan terkirim", 'status' => 200], 200);
        } catch (Exception $e) {
            DB::rollback();
            return $e;
        }
    }
}
