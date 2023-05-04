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
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_number');
            $table->string('name');
            $table->text('note')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('bank_id')->nullable();
            $table->integer('type')->nullable()->comment('1 = Cash / 2 = Bank');
            $table->double('initial_balance')->nullable();
            $table->integer('user_id')->index()->nullable();
            $table->integer('account_type')->nullable()->comment('1 = System / 2 = Employee / 3 = Client');
            $table->string('routing_no')->nullable();
            $table->Integer('status')->default(1)->comment('1 = Publish / 0 = Unpublish');
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
        Schema::dropIfExists('bank_accounts');
    }
};
