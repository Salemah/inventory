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
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->nullable();
                 $table->integer('warehouse_id')->index();
                 $table->integer('user_id')->index()->nullable();
                 $table->integer('supplier_id')->index()->nullable();
                 $table->integer('item')->nullable();
                 $table->string('total_qty')->nullable();
                 $table->string('total_discount')->nullable();
                 $table->string('total_tax')->nullable();
                 $table->string('order_tax_rate')->nullable();
                 $table->string('order_tax')->nullable();
                 $table->string('return_no')->nullable();
                 $table->string('invoice_no')->nullable();
                 $table->string('order_discount')->nullable();
                 $table->string('shipping_cost')->nullable();
                 $table->string('grand_total')->nullable();
                 $table->string('paid_amount')->nullable();
                 $table->string('status')->nullable();
                 $table->string('payment_status')->nullable();
                 $table->string('document')->nullable();
                 $table->string('note')->nullable();
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
        Schema::dropIfExists('purchase_returns');
    }
};
