<?php

namespace App\Exports;

use App\Models\Sampel;
use Maatwebsite\Excel\Concerns\FromCollection;

class SampelValidatedExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Sampel::all();
    }
}
