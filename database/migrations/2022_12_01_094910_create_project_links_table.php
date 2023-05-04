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
        Schema::create('project_links', function (Blueprint $table) {
            $table->id();
            $table->integer('project_id')->index();
            $table->string('cpanel_link')->nullable();
            $table->string('cpanel_password')->nullable();
            $table->string('web_link')->nullable();
            $table->string('git_link')->nullable();
            $table->json('role')->nullable();
            $table->json('user_email')->nullable();
            $table->json('user_password')->nullable();
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
        Schema::dropIfExists('project_links');
    }
};
