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
        Schema::create('client_reminders', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id')->index();
            $table->integer('status')->nullable();
            $table->string('date');
            $table->string('reminder_document')->nullable();
            $table->string('time')->nullable();
            $table->longText('reminder_note')->nullable();
            $table->integer('email_status')->nullable()->comment('1 = Not Send Reminder Email / 2= Send Reminder Email');
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
        Schema::dropIfExists('client_reminders');
    }
};
