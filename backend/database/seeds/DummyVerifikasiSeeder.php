<?php

use App\Models\Sampel;
use App\Models\SampelLog;
use Illuminate\Database\Seeder;

class DummyVerifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $this->getChunkOfSample()->each(function(Sampel $sampel){
            $sampelLog = SampelLog::create([
                'sampel_id'=> $sampel->getAttribute('id'),
                'sampel_status'=> 'pcr_sample_analyzed',
                'sampel_status_before'=> 'extraction_sample_sent',
                // 'metadata',
                // 'description'
            ]);
        });
    }

    private function getChunkOfSample()
    {
        return Sampel::query()->where('register_id', '!=', null)->get()->take(3);
    }
}
