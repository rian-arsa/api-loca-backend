<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            
            $table->bigInteger('users_id');
            $table->bigInteger('couriers_id')->nullable();
            $table->bigInteger('day_id')->nullable();

            $table->string('name');
            $table->string('profile');
            $table->string('image')->nullable();
            $table->string('url')->nullable();
            $table->string('username');
            $table->string('address');
            $table->longText('deskripsi')->nullable();
            $table->longText('catatan_toko')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
}
