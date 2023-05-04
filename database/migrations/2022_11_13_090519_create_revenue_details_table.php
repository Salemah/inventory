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
        Schema::create('revenue_details', function (Blueprint $table) {
            $table->id();
            $table->integer('revenue_id')->index();
            $table->string('revenue_category')->nullable();
            $table->string('revenue_date');
            $table->string('document')->nullable();
            $table->text('description')->nullable();
            $table->double('amount');
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
        Schema::dropIfExists('revenue_details');
    }
};
