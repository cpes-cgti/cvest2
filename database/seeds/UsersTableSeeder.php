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
            'password' => bcrypt('123456'),
            'profile' => 4,
        ]);
        User::create([
            'name' => 'Thamiris Kassia de Barros Queiroz',
            'email' => 'thamiris.queiroz@reitoria.ifpe.edu.br ',
            'password' => bcrypt('123456'),
            'profile' => 3,
        ]);
        User::create([
            'name' => 'Andrea Christianne Gomes Barretto',
            'email' => 'andrea.barretto@abreuelima.ifpe.edu.br',
            'password' => bcrypt('123456'),
            'profile' => 3,
        ]);
        User::create([
            'name' => 'Thayse Carolina Ferreira Paraiso',
            'email' => 'thayseparaiso@recife.ifpe.edu.br',
            'password' => bcrypt('123456'),
            'profile' => 2,
        ]);
        User::create([
            'name' => 'Maviael Calado Ramalho',
            'email' => 'maviael.calado@pesqueira.ifpe.edu.br',
            'password' => bcrypt('123456'),
            'profile' => 0,
        ]);
    }
}
