<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('users')->insert([
            'name'=> 'Jose',
            'lastname'=> 'Linares',
            'email'=> 'prueba@gmail.com',
            'phone'=> '04244137923',
            'photo'=> 'users/1684362227jpg',
            'password'=> Hash::make('12345678'),
            'document'=> '24548538',
            'short_address'=> 'San Blas, Valencia',
            'role_id'=> 3,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
