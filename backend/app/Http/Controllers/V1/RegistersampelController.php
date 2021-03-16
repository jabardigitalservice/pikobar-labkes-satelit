<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterSampelRequest;
use App\Http\Resources\RegisterSampelDetailResource;
use App\Models\RegisterLog;
use App\Models\JenisSampel;
use App\Models\Pasien;
use App\Models\PasienRegister;
use App\Models\PengambilanSampel;
use App\Models\Register;
use App\Models\Sampel;
use App\Traits\RegisterSampelTrait;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class RegistersampelController extends Controller
{
    use RegisterSampelTrait;

    public function store(RegisterSampelRequest $request)
    {
        DB::beginTransaction();
        try {
            $register = new Register();
            $register->fill($this->getRequestRegister($request) +
                $this->getUser($request) +
                $this->getNomorRegister()
            );
            $register->save();
            $pasien = new Pasien();
            $pasien->fill($this->getRequestPasien($request) + $this->getUser($request));
            $pasien->save();
            PasienRegister::create(['pasien_id' => $pasien->id, 'register_id' => $register->id]);
            $pengambilan_sampel = PengambilanSampel::create($this->getRequestPengambilanSampel($request));
            $sampel = new Sampel();
            $sampel->fill(
                $this->getRequestSampel($request, $register, $pengambilan_sampel) +
                $this->getUser($request) +
                $this->getSampelStatus()
            );
            $sampel->save();
            DB::commit();
            return response()->json(['message' => 'Registrasi Sampel Berhasil Ditambahkan'], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }

    public function update(RegisterSampelRequest $request, Register $register, Pasien $pasien)
    {
        DB::beginTransaction();
        try {
            $sampel = $register->sampel;
            $registerOrigin = clone $register;
            $pasienOrigin = clone $pasien;
            $sampelOrigin = clone $sampel;
            $this->setUpdate($request, $register, $pasien, $sampel);
            $registerChanges = $register->getChanges();
            $pasienChanges = $pasien->getChanges();
            $sampelChanges = $sampel->getChanges();
            $register->logs()->create([
                "user_id" => $request->user()->id,
                "info" => json_encode([
                    "register" => $this->getLogsRegister($registerOrigin, $registerChanges),
                    "sampel" => $this->getLogsSampel($sampelOrigin, $sampelChanges),
                    "pasien" => $this->getLogsPasien($pasienOrigin, $pasienChanges)
                ])
            ]);
            DB::commit();
            return response()->json(['message' => 'Data Register Berhasil Diubah']);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    private function setUpdate($request, $register, $pasien, $sampel)
    {
        $register->fill($this->getRequestRegister($request) + $this->getUser($request));
        $register->save();
        $pasien->fill($this->getRequestPasien($request) + $this->getUser($request));
        $pasien->save();
        $sampel->pengambilanSampel->update(['catatan' => $request->get('reg_keterangan')]);
        $sampel->fill($this->getRequestSampel($request, $register, $sampel->pengambilanSampel) + $this->getUser($request));
        $sampel->save();
    }

    public function show(Register $register)
    {
        return new RegisterSampelDetailResource($register);
    }

    public function logs($register_id)
    {
        $model = RegisterLog::where('register_id', $register_id)
            ->join('users', 'users.id', 'register_logs.user_id')
            ->whereRaw("(
                        info->>'pasien' <> '[]'::text or
                        info->>'register' <> '[]'::text or
                        info->>'sampel' <> '[]'::text
                        )")
            ->select('info', 'register_logs.created_at', 'users.name as updated_by')
            ->get();
        return response()->json(['result' => $model]);
    }

    public function destroy(Register $register)
    {
        $register->delete();
        return response()->json(['message' => 'Berhasil menghapus data sampel']);
    }
}
