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
        Schema::create('inventory_customers', function (Blueprint $table) {
            $table->id();
            $table->integer('country_id')->nullable();
            $table->integer('state_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('area_id')->nullable();
            $table->integer('postal_code')->nullable();
            $table->string('company_name')->nullable();
            $table->string('name');
            $table->string('customer_type_priority')->nullable()->default(1)->comment('1 = First / 2 = Second / 3 = Third');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('tax_number')->nullable();
            $table->string('contact_person')->nullable();
            $table->text('address');
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
        Schema::dropIfExists('inventory_customers');
    }
};
