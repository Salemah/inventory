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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->integer('task_type')->nullable()->index()->comment('1-project, 2 Module');
            $table->integer('task_type_id')->index()->nullable();
            $table->integer('project_id')->index()->nullable();
            $table->json('reporting_person_id')->nullable();
            $table->json('assign_employee_id')->nullable();
            $table->integer('status')->comment('1 = Up Coming / 2 = ongoing / 3 = complete / 4 = Cancel');
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
        Schema::dropIfExists('tasks');
    }
};
