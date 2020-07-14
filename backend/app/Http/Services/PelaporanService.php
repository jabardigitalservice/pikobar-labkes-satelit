<?php
namespace App\Http\Services;

use Config;
use Illuminate\Support\Facades\Http;

class PelaporanService
{

    public function pendaftar_rdt($keyword)
    {
        $url = Config::get('services.pelaporan.url');

        return Http::get($url, [
            'data_source' => 'pelaporan',
            'mode' => 'bykeyword',
            'keyword' => $keyword,
            'api_key' => Config::get('services.pelaporan.api_key'),
        ]);
    }
}
