<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
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

            $table->bigInteger('users_id');
            $table->bigInteger('store_id');
            $table->bigInteger('metode_pembayaran_id');

            $table->string('invoice')->nullable();
            $table->string('nomor_resi')->nullable();
            $table->string('jasa_antar');

            $table->float('total_shop')->default(0);
            $table->float('diskon_price')->default(0);
            $table->float('shipping_price')->default(0);
            $table->float('shipping_diskon')->default(0);
            $table->float('total_price')->default(0);

            $table->string('status')->default('BELUM BAYAR');

            $table->longText('catatan')->nullable();

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
        Schema::dropIfExists('transactions');
    }
}
