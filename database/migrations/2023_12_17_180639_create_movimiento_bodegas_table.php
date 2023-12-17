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
        Schema::create('movimiento_bodegas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('bodega_origen')->nullable();
            $table->unsignedBigInteger('bodega_destino')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('bodega_origen')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('bodega_destino')->references('id')->on('warehouses')->onDelete('cascade');
            $table->integer('cantidad');
            $table->string('observaciones',500)->default('No Aplica');
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
        Schema::dropIfExists('movimiento_bodegas');
    }
};
