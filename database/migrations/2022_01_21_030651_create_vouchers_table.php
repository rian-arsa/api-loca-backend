<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('store_id');

            $table->string('name');
            $table->string('kode');
            $table->string('type');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->float('minimum');
            $table->float('kuota');
            $table->longText('deskripsi');
            $table->string('status');

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
        Schema::dropIfExists('vouchers');
    }
}
