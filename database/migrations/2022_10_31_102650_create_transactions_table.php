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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('account_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->integer('project_id')->nullable();
            $table->integer('received_by')->nullable();
            $table->integer('user_type')->nullable();
            $table->integer('emp_salaryId')->nullable();
            $table->integer('investment_id')->index()->nullable();
            $table->integer('investor_id')->index()->nullable();
            $table->integer('loan_id')->index()->nullable();
            $table->integer('supplier_id')->index()->nullable();
            $table->integer('purchase_id')->index()->nullable();
            $table->integer('sale_id')->index()->nullable();
            $table->integer('transaction_account_type')->nullable()->comment('1 = Cash / 2 = Bank');
            $table->integer('loan_author_id')->index()->nullable();
            $table->integer('expense_id')->index()->nullable();
            $table->integer('revenue_id')->index()->nullable();
            $table->string('transaction_title');
            $table->date('transaction_date')->nullable();
            $table->string('reference')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('transaction_purpose')->comment('0 = Initial Balance , 1 = Withdraw / 2 = Deposit / 3 = Revenue / 4 = Given Payment / 5 = Expense / 6 =  Fund-Transfer (Cash-In) / 7 =  Fund-Transfer (Cash-Out) / 8 = Cash-In / 9 = Investment /10 = Investment-Return /11 = Profit-Return /12 = Giving-Loan /13 = Taking-Loan /14 = Return Giving Loan /15 = Return Taking Loan /16 = Project Budget Reciept / 17 = employee payment');
            $table->string('cheque_number')->nullable();
            $table->tinyInteger('transaction_type')->comment('1= Debit / 2= Credit');
            $table->tinyInteger('amount_type')->comment('1= Full / 2= Partial');
            $table->double('amount');
            $table->tinyInteger('status')->default(1)->comment('1 = Active / 0 = Deactivate');
            $table->json('access_id')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('transactions');
    }
};
