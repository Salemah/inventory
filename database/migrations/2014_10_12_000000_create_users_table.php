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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('role_id')->nullable();
            $table->string('mobile')->nullable();
            $table->boolean('record_access');
            $table->json('access_id');
            $table->integer('user_id')->nullable();
            $table->integer('user_type')->nullable()->comment('1 = Employee / 2 = Client');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('created_by');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
