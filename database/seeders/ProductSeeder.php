<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('products')->insert([
            'name' => 'Iphone 13',
            'description' => 'Mobile Phone',
            'price' => 1300
        ]);
        DB::table('products')->insert([
            'name' => 'Iphone 11',
            'description' => 'Mobile Phone',
            'price' => 1200
        ]);
        DB::table('products')->insert([
            'name' => 'Iphone 10',
            'description' => 'Mobile Phone',
            'price' => 1000
        ]);
    }
}
