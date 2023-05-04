<?php

namespace Database\Seeders;

use Database\Seeders\CitiesTableSeeder;
use Database\Seeders\StatesTableSeeder;
use Database\Seeders\CountriesTableSeeder;
use Database\Seeders\BloodGroupTableSeeder;
use Database\Seeders\IdentityTableSeeder;
use Database\Seeders\DesignationTableSeeder;
use Database\Seeders\DepartmentTableSeeder;
use Database\Seeders\ClientPriorityTableSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(1)->create();

        $this->call([
            CountriesTableSeeder::class,
            StatesTableSeeder::class,
            CitiesTableSeeder::class,
            BloodGroupTableSeeder::class,
            IdentityTableSeeder::class,
            DesignationTableSeeder::class,
            DepartmentTableSeeder::class,
            ClientTypeTableSeeder::class,
            ContactThroughTableSeeder::class,
            InterestedOnTableSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            ProjectCategorySeeder::class,
            BankSeeder::class,
            BankSeeder::class,
            ClientPriorityTableSeeder::class,
        ]);

    }
}
