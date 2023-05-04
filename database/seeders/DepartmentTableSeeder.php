<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class DepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('departments')->delete();
        $department = array(
            array('name' => "Software",'status' => 1,'access_id' => "1",'created_by' => 1),
            array('name' => "Marketing",'status' => 1,'access_id' => "1",'created_by' => 1),
            array('name' => "Hr",'status' => 1,'access_id' => "1",'created_by' => 1),
        );
        DB::table('departments')->insert($department);
    }
}
