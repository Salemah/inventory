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
        Schema::create('paid_leaves', function (Blueprint $table) {
            $table->id();
            $table->integer('days')->index();
            $table->integer('paid_per_day')->index();
            $table->integer('total_amount')->nullable();
            $table->longText('description')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1 = Active / 0 = Deactivate');
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
        Schema::dropIfExists('paid_leaves');
    }
};
