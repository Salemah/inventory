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
        Schema::create('investor_identities', function (Blueprint $table) {
            $table->id();
            $table->integer('investor_id')->index();
            $table->integer('id_type_id')->index();
            $table->integer('id_no')->index();
            $table->integer('user_type')->nullable()->comment('1 = investor / 2 = loan Authority');
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
        Schema::dropIfExists('investor_identities');
    }
};
