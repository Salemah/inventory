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
        Schema::create('employee_identities', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->index();
            $table->integer('id_type_id')->index();
            $table->integer('id_no')->index();
            $table->integer('user_type')->nullable()->comment('1 = Employee / 2 = Client');
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
        Schema::dropIfExists('employee_identities');
    }
};
