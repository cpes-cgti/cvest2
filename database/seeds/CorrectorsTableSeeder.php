<?php

use Illuminate\Database\Seeder;
use App\Models\Corrector;

class CorrectorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Corrector::create([
            'cpf' => '03256858430',
            'siape' => '1804315',
            'user_id' => 1,
        ]);
        Corrector::create([
            'cpf' => '01436653401',
            'siape' => '1819455',
            'user_id' => 2,
        ]);
        Corrector::create([
            'cpf' => '03702299408',
            'siape' => '2765325',
            'user_id' => 3,
        ]); */
        Corrector::create([
            'cpf' => '07599725407',
            'siape' => '1150712',
            'user_id' => 4,
        ]);
        
    }
}
