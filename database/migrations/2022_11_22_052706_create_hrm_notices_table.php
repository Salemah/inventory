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
        Schema::create('hrm_notices', function (Blueprint $table) {
            $table->id();
            $table->string('notice_title');
            $table->string('notice_date');
            $table->json('department_id');
            $table->json('employee_id');
            $table->json('document_title')->nullable();
            $table->json('document_file')->nullable();
            $table->json('meetings')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1 = Active / 0 = Deactivate');
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
        Schema::dropIfExists('hrm_notices');
    }
};
