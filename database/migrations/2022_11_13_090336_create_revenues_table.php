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
        Schema::create('revenues', function (Blueprint $table) {
            $table->id();
            $table->string('revenue_invoice_date');
            $table->string('revenue_invoice_no');
            $table->integer('revenue_by')->index()->nullable();
            $table->integer('client')->index()->nullable();
            $table->integer('status')->comment('1= Pending / 2= Approved')->nullable();
            $table->string('adjustment_type')->nullable();
            $table->string('adjustment_balance')->nullable();
            $table->string('vat_type')->nullable()->comment('1= Including / 2= Excluding');
            $table->double('vat_rate')->nullable();
            $table->json('document')->nullable();
            $table->json('document_title')->nullable();
            $table->double('total');
            $table->integer('transaction_way')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('revenues');
    }
};
