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

        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->integer('client_business_category_id')->index()->nullable();
            $table->text('address')->nullable();
            $table->string('phone_primary');
            $table->string('phone_secondary')->nullable();
            $table->string('client_type_priority')->nullable();
            $table->string('email')->nullable();
            $table->integer('client_type')->nullable();
            $table->integer('contact_through')->nullable();
            $table->json('contact_person')->nullable();
            $table->integer('interested_on')->nullable();
            $table->string('status')->nullable();
            $table->string('present_address')->nullable();
            $table->string('permanent_address')->nullable();
            $table->string('website')->nullable();
            $table->integer('country_id')->nullable()->index();
            $table->integer('state_id')->nullable()->index();
            $table->integer('city_id')->nullable()->index();
            $table->string('zip')->nullable()->index();
            $table->integer('country_id_others')->nullable()->index();
            $table->integer('state_id_others')->nullable()->index();
            $table->integer('city_id_others')->nullable()->index();
            $table->string('zip_others')->nullable()->index();
            $table->text('description')->nullable();
            $table->boolean('is_assign')->default(false);
            $table->json('assign_to')->nullable();
            $table->json('own_assign')->nullable();
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
        Schema::dropIfExists('clients');
    }
};
