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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('brand_id')->index()->nullable();
            $table->string('category_id')->index()->nullable();
            $table->integer('unit_id')->index()->nullable();
            $table->integer('purchase_unit_id')->index()->nullable();
            $table->integer('sale_unit_id')->index()->nullable();
            $table->integer('tax_id')->index()->nullable();
            $table->integer('tax_method')->nullable();
            $table->string('cost')->nullable();
            $table->string('price')->nullable();
            $table->string('qty')->nullable();
            $table->string('product_list')->nullable();
            $table->string('qty_list')->nullable();
            $table->integer('is_batch')->nullable();
            $table->integer('is_variant')->nullable();
            $table->integer('is_diffPrice')->nullable();
            $table->string('alert_quantity')->nullable();
            $table->longText('image');
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
        Schema::dropIfExists('products');
    }
};
