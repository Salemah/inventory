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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_code')->nullable();
            $table->integer('parent_id')->index()->nullable();
            $table->string('project_title')->nullable();
            $table->string('project_date');
            $table->integer('kanban_order')->nullable();
            $table->string('type')->index()->comment('1 = Project / 2 = Module / 3 = Task');
            $table->integer('client_id')->index()->nullable();
            $table->integer('project_category')->index()->nullable();
            $table->integer('project_type')->comment('1 = Own / 2 = Client / 3 = Public ');
            $table->integer('project_priority')->comment(' 1= first / 2 = second / 3 = Third')->nullable();
            $table->json('reporting_person_id')->nullable();
            $table->json('department')->nullable();
            $table->json('assign_employee_id')->nullable();
            $table->integer('status')->comment('1 = Up Coming / 2 = ongoing / 3 = complete / 4 = Cancel / 5 =  On Hold');
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
        Schema::dropIfExists('projects');
    }
};
