<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Clemente',
                'email' => 'clemenclay@gmail.com',
                'email_verified_at' => null,
                'password' => '$2y$12$Cc5QB864GpnSwhLCh4b92.PaieeTOBUwqY0OWs.WATzgKNSaWyQrS',
                'remember_token' => 'hYjznraL5LVZqYl3OMYUeGmEe6ld5Ch9430wwPOaQuqAIm11idXjSzYOtoiv',
                'created_at' => '2024-10-08 03:58:00',
                'updated_at' => '2024-10-10 02:52:19',
            ],
            [
                'id' => 2,
                'name' => 'Nacho',
                'email' => 'iscarano@buenosaires.gob.ar',
                'email_verified_at' => null,
                'password' => '$2y$12$FB9OX2Aeiwiw4XFQOG2Hke3h3gq7RMD/Z8ebaTbyHjpsDPNLgIcvC',
                'remember_token' => null,
                'created_at' => '2024-10-10 04:15:31',
                'updated_at' => '2024-10-10 04:15:31',
            ],
        ]);
    }
}
