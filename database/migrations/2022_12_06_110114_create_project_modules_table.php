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
        Schema::create('project_modules', function (Blueprint $table) {
            $table->id();
            // $table->integer('project_id')->index();
            // $table->integer('project_duration_id')->index();
            // $table->string('module_name')->nullable();
            // $table->string('module_start_date')->nullable();
            // $table->string('module_end_date')->nullable();
            // $table->string('module_estimate_day')->nullable();
            // $table->string('module_estimate_hour')->nullable();
            // $table->string('module_total_day')->nullable();
            // $table->string('module_final_hour')->nullable();
            // $table->string('adjustment_hour')->nullable();
            // $table->string('estimate_hour_per_day')->nullable();
            // $table->string('adjustment_type')->nullable()->comment('1 = Addition / 2 = Subtraction');
            // $table->integer('status')->nullable()->comment('1 = Up Coming / 2 = ongoing / 3 = complete / 4 = Cancel / 5 = On Hold');
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
        Schema::dropIfExists('project_modules');
    }
};
