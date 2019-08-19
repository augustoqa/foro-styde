<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\User::class)->create([
            'username' => 'Ducke',
            'first_name' => 'Cesar',
            'last_name' => 'Acual',
            'email' => 'chechaacual@gmail.com',
            'role' => 'admin',
        ]);
    }
}
