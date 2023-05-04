<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class IdentityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('identities')->delete();
        $identities = array(
            array('name' => "Nid",'status' => 1,'access_id' => "1"),
            array('name' => "Passport",'status' => 1,'access_id' => "1"),
            array('name' => "Driving License",'status' => 1,'access_id' => "1"),
        );
        DB::table('identities')->insert($identities);
    }
}
