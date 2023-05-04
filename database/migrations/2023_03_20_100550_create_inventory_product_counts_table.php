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
        Schema::create('inventory_product_counts', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->nullable();
            $table->string('date')->nullable();
            $table->string('status')->nullable();
            $table->integer('warehouse_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('product_id')->index()->nullable();
            $table->integer('purchase_id')->index()->nullable();
            $table->integer('purchase_return_id')->index()->nullable();
            $table->integer('sale_unit_id')->index()->nullable();
            $table->integer('sale_return_id')->index()->nullable();
            $table->integer('stock_count')->nullable();
            $table->integer('sale_qty')->nullable();
            $table->integer('purchase_qty')->nullable();
            $table->integer('purchase_return_qty')->nullable();
            $table->integer('sale_return_qty')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->integer('product_batch_id')->nullable();
            $table->integer('sale_id')->nullable();
            $table->integer('variant_id')->nullable();
            $table->integer('purchase_unit_id')->nullable();
            $table->integer('tax_rate')->nullable();
            $table->integer('stock_in')->nullable();
            $table->integer('stock_out')->nullable();
            $table->integer('purchase_price')->nullable();
            $table->integer('sale_price')->nullable();
            $table->integer('total')->nullable();
            $table->integer('net_unit_cost')->nullable();
            $table->integer('selling_price')->nullable();
            $table->integer('discount')->nullable();
            $table->string('paid_amount')->nullable();
            $table->integer('payment_status')->nullable();
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
        Schema::dropIfExists('inventory_product_counts');
    }
};
