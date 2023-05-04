<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ClientPriorityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('priorities')->delete();
        $client_type = array(
            array('name' => "First",'status'=>1,'access_id' => "1",'created_by' => 1),
            array('name' => "Second",'status'=>1,'access_id' => "1",'created_by' => 1),
            array('name' => "Third",'status'=>1,'access_id' => "1",'created_by' => 1),

        );
        DB::table('priorities')->insert($client_type);
    }
}
