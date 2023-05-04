<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class DesignationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('designations')->delete();
        $designation = array(
            array('name' => "Intern",'status' => 1,'access_id' => "1",'created_by' => 1),
            array('name' => "Junior",'status' => 1,'access_id' => "1",'created_by' => 1),
            array('name' => "Senior",'status' => 1,'access_id' => "1",'created_by' => 1),
        );
        DB::table('designations')->insert($designation);
    }
}
