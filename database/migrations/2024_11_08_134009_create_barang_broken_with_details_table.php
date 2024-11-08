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
        Schema::create('barang_broken', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Track the user who created this transaction
            $table->string('invoice_number')->unique(); // Invoice number
            $table->date('tanggal_broken');
            $table->timestamps();

            // Foreign key to users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Create the barang_broken_items table
        Schema::create('barang_broken_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_broken_id'); // Link to barang_broken
            $table->unsignedBigInteger('barang_id'); // Link to barang
            $table->unsignedBigInteger('user_id'); // Track the user who created this item entry
            $table->integer('qty'); // Quantity added
            $table->timestamps();

            // Foreign keys
            $table->foreign('barang_broken_id')->references('id')->on('barang_broken')->onDelete('cascade');
            $table->foreign('barang_id')->references('id')->on('barang')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Drop barang_broken_items table first due to foreign key constraints
        Schema::dropIfExists('barang_broken_items');
        Schema::dropIfExists('barang_broken');
    }
};
