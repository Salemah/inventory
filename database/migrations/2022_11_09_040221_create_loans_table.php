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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->integer('loan_author_id')->index();
            $table->string('loan_title');
            $table->string('loan_date');
            $table->string('loan_amount');
            $table->integer('loan_type')->comment('1-Taking,2-Giving');
            $table->string('transaction_way')->comment('1-Cash,2-Bank');
            $table->string('duration')->nullable();
            $table->string('interest_rate')->nullable();
            $table->integer('negotiator_id')->index()->nullable();
            $table->text('note')->nullable();
            $table->integer('created_by')->index();
            $table->integer('status')->default(0)->comment(' 1 = flat rate / 0 =  No Flat Rate');
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
        Schema::dropIfExists('loans');
    }
};
