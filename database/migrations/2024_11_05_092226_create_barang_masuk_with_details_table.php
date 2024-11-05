<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_masuk', function (Blueprint $table) {
            // Header fields for barang_masuk
            $table->id();
            $table->unsignedBigInteger('purchase_order_id'); // Reference to the purchase order
            $table->unsignedBigInteger('user_id'); // User who processed barang masuk
            $table->date('tanggal_masuk'); // Date of barang masuk
            $table->string('note')->nullable(); // Additional notes
            $table->timestamps();

            // Foreign keys
            $table->foreign('purchase_order_id')->references('id')->on('purchase_order')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('barang_masuk_details', function (Blueprint $table) {
            // Detail fields for barang_masuk_items
            $table->id();
            $table->unsignedBigInteger('barang_masuk_id'); // Link to barang_masuk (header)
            $table->unsignedBigInteger('barang_id'); // Link to barang (item)
            $table->integer('qty'); // Quantity expected from PO
            $table->boolean('qty_verified')->default(false); // Verified status, default unchecked (false)
            $table->timestamps();

            // Foreign keys
            $table->foreign('barang_masuk_id')->references('id')->on('barang_masuk')->onDelete('cascade');
            $table->foreign('barang_id')->references('id')->on('barang')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // Drop details table first due to foreign key dependency
        Schema::dropIfExists('barang_masuk_details');
        Schema::dropIfExists('barang_masuk');
    }
};
