<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ClientTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('client_types')->delete();
        $client_type = array(
            array('name' => "Alen",'priority'=>1,'status'=>1,'access_id' => "1",'created_by' => 1),
            array('name' => "Suvo",'priority'=>2,'status'=>1,'access_id' => "1",'created_by' => 1),
            array('name' => "Sifat",'priority'=>3,'status'=>1,'access_id' => "1",'created_by' => 1),
            array('name' => "Arsad",'priority'=>1,'status'=>1,'access_id' => "1",'created_by' => 1),
            array('name' => "Abir",'priority'=>2,'status'=>1,'access_id' => "1",'created_by' => 1),
            array('name' => "Arafat",'priority'=>3,'status'=>1,'access_id' => "1",'created_by' => 1),
        );
        DB::table('client_types')->insert($client_type);
    }
}
