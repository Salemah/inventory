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
        Schema::create('project_durations', function (Blueprint $table) {
            $table->id();
            $table->integer('duration_type')->index()->comment('1 = Project / 2 = Module / 3 = Task');
            $table->integer('duration_type_id')->index()->nullable();
            $table->integer('module_duration_id')->nullable();
            $table->integer('name')->index()->nullable();
            $table->string('project_id')->index()->nullable();
            $table->integer('on_hold')->nullable()->default(0)->comment('0= No / 2 = On Hold');
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->string('vacation_day')->nullable();
            $table->string('estimate_day')->nullable();
            $table->string('final_day')->nullable();
            $table->string('estimate_hour_per_day')->nullable();
            $table->string('estimate_hour')->nullable();
            $table->string('adjustment_hour')->nullable();
            $table->string('adjustment_type')->nullable()->comment('1 = Addition / 2 = Subtraction');
            $table->string('final_hour')->nullable();
            $table->integer('status')->nullable()->comment('1 = Up Coming / 2 = ongoing / 3 = complete / 4 = Cancel / 5 = On Hold');
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
        Schema::dropIfExists('project_durations');
    }
};
