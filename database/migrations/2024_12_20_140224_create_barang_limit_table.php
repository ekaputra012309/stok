<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barang_limit', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_id'); 
            $table->unsignedBigInteger('user_id'); 
            $table->integer('qtyLimit'); 
            $table->enum('status', ['1', '0'])->default('1'); 
            $table->timestamps();

            // Foreign keys
            $table->foreign('barang_id')->references('id')->on('barang')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_limit');
    }
};
