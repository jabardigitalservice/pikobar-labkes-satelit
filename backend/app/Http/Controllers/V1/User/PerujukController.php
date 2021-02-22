<?php

namespace App\Http\Controllers\V1\User;

use App\Enums\UserStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\PerujukRegisterRequest;
use App\Notifications\RegisterPerujukNotification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerujukController extends Controller
{
    public function store(PerujukRegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::create($request->validated() + [
                'status' => UserStatusEnum::ACTIVE(),
                'register_at' => now(),
            ]);
            $password = $request->input('password');
            $user->notify(new RegisterPerujukNotification($password));
            DB::commit();
            return response()->json(["message" => "Registrasi Berhasil"]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function update(User $user, Request $request)
    {
        $request->validate([
            'perujuk_id' => 'required|exists:labkes.fasyankes,id'
        ]);
        $user->update($request->only('perujuk_id'));
        return $user;
    }
}
