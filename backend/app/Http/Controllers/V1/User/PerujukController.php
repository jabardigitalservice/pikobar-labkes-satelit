<?php

namespace App\Http\Controllers\V1\User;

use App\Enums\UserStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\PerujukRegisterRequest;
use App\Notifications\RegisterPerujukNotification;
use App\User;
use Illuminate\Http\Request;

class PerujukController extends Controller
{
    public function store(PerujukRegisterRequest $request)
    {
        $user = User::create($request->validated() + [
            'status' => UserStatusEnum::ACTIVE(),
            'register_at' => now(),
        ]);
        $password = $request->input('password');
        $user->notify(new RegisterPerujukNotification($password));
        return response()->json(["message" => "Registrasi Berhasil"]);
    }

    public function update(User $user, Request $request)
    {
        $request->validate([
            'perujuk_id' => 'required|exists:fasyankes,id'
        ]);
        $user->update($request->only('perujuk_id'));
        return $user;
    }
}
