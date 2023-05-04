<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->index()->nullable();
            $table->string('image')->nullable();
            $table->string('name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_primary')->index()->nullable();
            $table->string('phone_secondary')->nullable();
            $table->string('website')->nullable();
            $table->integer('gender')->index()->nullable()->comment('1-Male, 2-Female, 3-Others');
            $table->string('joining_date')->nullable();
            $table->string('job_left_date')->nullable();
            $table->string('blood_group')->nullable();
            $table->double('joining_salary')->nullable();
            $table->double('current_salary')->nullable();
            $table->double('gross_salary')->nullable();
            $table->string('present_address')->nullable();
            $table->string('permanent_address')->nullable();
            $table->integer('warehouse')->index()->nullable();
            $table->integer('country_id')->nullable()->index();
            $table->integer('state_id')->nullable()->index();
            $table->integer('city_id')->nullable()->index();
            $table->string('zip')->nullable()->index();
            $table->integer('designation')->index()->nullable();
            $table->integer('department')->index()->nullable();
            $table->integer('marital_status')->nullable()->comment('1-Married, 2-Unmarried, 3-Separated');
            $table->string('date_of_birth')->nullable();
            $table->integer('role')->nullable();
            $table->json('academic_qualification')->nullable();
            $table->json('documents')->nullable();
            $table->json('experience')->nullable();
            $table->json('certification')->nullable();
            $table->text('description')->nullable();
            $table->integer('created_by')->index();
            $table->integer('updated_by')->nullable()->index();
            $table->json('access_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
