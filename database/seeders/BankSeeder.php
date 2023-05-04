<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('banks')->delete();
        $department = array(
            array('bank_name' => "Ucb",'bank_code'=> 23423,'description'=>"Rajbari",'status' => 1,'access_id' => "1",'created_by' => 1),
            array('bank_name' => "Brac",'bank_code'=> 23,'description'=>"Faridpur",'status' => 1,'access_id' => "1",'created_by' => 1),
        );
        DB::table('banks')->insert($department);
    }
}
