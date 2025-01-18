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
        Schema::create('barang_keluar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Track the user who created this transaction
            $table->string('invoice_number')->unique(); // Invoice number
            $table->string('po_number')->unique();
            $table->unsignedBigInteger('customer_id');
            $table->date('tanggal_keluar');
            $table->timestamps();

            // Foreign key to users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });

        // Create the barang_keluar_items table
        Schema::create('barang_keluar_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_keluar_id'); // Link to barang_keluar
            $table->unsignedBigInteger('barang_id'); // Link to barang
            $table->unsignedBigInteger('user_id'); // Track the user who created this item entry
            $table->integer('qty'); // Quantity added
            $table->string('remarks')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('barang_keluar_id')->references('id')->on('barang_keluar')->onDelete('cascade');
            $table->foreign('barang_id')->references('id')->on('barang')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Drop barang_keluar_items table first due to foreign key constraints
        Schema::dropIfExists('barang_keluar_items');
        Schema::dropIfExists('barang_keluar');
    }
};
