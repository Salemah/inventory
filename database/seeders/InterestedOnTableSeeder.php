<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class InterestedOnTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('interested_ons')->delete();
        $interested_on = array(
            array('name' => "hr",'status'=>1,'access_id' => "1",'created_by' => 1),
            array('name' => "robot",'status'=>1,'access_id' => "1",'created_by' => 1),
            array('name' => "space",'status'=>1,'access_id' => "1",'created_by' => 1),
        );
        DB::table('interested_ons')->insert($interested_on);
    }
}
