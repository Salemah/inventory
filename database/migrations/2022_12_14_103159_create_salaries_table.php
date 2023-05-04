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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->integer('allowance_id')->nullable();
            $table->string('salary_month');
            $table->double('basic_salary')->nullable();
            $table->double('home_allowance')->nullable();
            $table->double('transport_allowance')->nullable();
            $table->double('medical_allowance')->nullable();
            $table->double('mobile_allowance')->nullable();
            $table->double('gross_salary')->nullable();;
            $table->longText('description')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1 = Active / 0 = Deactivate');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('salaries');
    }
};
