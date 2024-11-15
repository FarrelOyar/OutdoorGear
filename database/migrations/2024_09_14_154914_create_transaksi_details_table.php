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
        Schema::create('transaksi_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_transaksi')->unsigned();
            $table->unsignedBigInteger('id_barang')->unsigned();
            $table->integer('qty');
            $table->unsignedBigInteger('id_denda')->unsigned();
            $table->timestamps();

            $table->foreign('id_transaksi')->references('id')->on('transaksis');
            $table->foreign('id_barang')->references('id')->on('barangs');
            $table->foreign('id_denda')->references('id')->on('dendas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi_details');
    }
};
