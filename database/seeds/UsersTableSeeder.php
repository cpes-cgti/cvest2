<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Jobson Tenório do Nascimento',
            'email' => 'jobson.nascimento@pesqueira.ifpe.edu.br',
            /* 'password' => bcrypt(str_random(10)), */
            'password' => bcrypt('03256858430'),
        ]);
        User::create([
            'name' => 'Thamiris Kassia de Barros Queiroz',
            'email' => 'thamiris.queiroz@reitoria.ifpe.edu.br ',
            'password' => bcrypt('01436653401'),
        ]);
        User::create([
            'name' => 'Andrea Christianne Gomes Barretto',
            'email' => 'andrea.barretto@abreuelima.ifpe.edu.br',
            'password' => bcrypt('03702299408'),
        ]);
        User::create([
            'name' => 'Thayse Carolina Ferreira Paraiso',
            'email' => 'thayseparaiso@recife.ifpe.edu.br',
            'password' => bcrypt('07599725407'),
        ]);
    }
}
