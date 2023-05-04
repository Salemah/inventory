<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ContactThroughTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('contact_throughs')->delete();
        $contact_through = array(
            array('name' => "Sam",'status'=>1,'access_id' => "1",'created_by' => 1),
            array('name' => "avc",'status'=>1,'access_id' => "1",'created_by' => 1),
            array('name' => "scania",'status'=>1,'access_id' => "1",'created_by' => 1),
        );
        DB::table('contact_throughs')->insert($contact_through);
    }
}
