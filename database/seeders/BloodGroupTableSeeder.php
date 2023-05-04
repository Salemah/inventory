<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class BloodGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('blood_groups')->delete();
        $blood_groups = array(
            array('name' => "B+"),
            array('name' => "B-"),
            array('name' => "A+"),
            array('name' => "A-"),
            array('name' => "O+"),
            array('name' => "O-"),
            array('name' => "AB+"),
            array('name' => "AB-"),
        );
        DB::table('blood_groups')->insert($blood_groups);
    }
}
