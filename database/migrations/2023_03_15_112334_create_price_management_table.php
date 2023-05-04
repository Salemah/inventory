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
        Schema::create('price_management', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('cost');
            $table->integer('price');
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
        Schema::dropIfExists('price_management');
    }
};
