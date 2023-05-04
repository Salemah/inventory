<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ProjectCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('project_categories')->delete();
        $project_categories = array(
            array('name' => "Hrm",'status' => 1,'created_by' => 1,),
            array('name' => "CRM",'status' => 1,'created_by' => 1,),
            array('name' => "Office",'status' => 1,'created_by' => 1,),
        );
        DB::table('project_categories')->insert($project_categories);
    }
}
