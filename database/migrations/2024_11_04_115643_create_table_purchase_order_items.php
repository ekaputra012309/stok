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
        Schema::create('purchase_order', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Track the user who created this transaction
            $table->string('invoice_number')->unique(); // Invoice number
            $table->string('status_order')->nullable();
            $table->string('approveby')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();

            // Foreign key to users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Create the purchase_order_items table
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_order_id'); // Link to purchase_order
            $table->unsignedBigInteger('barang_id'); // Link to barang
            $table->unsignedBigInteger('user_id'); // Track the user who created this item entry
            $table->integer('qty'); // Quantity added
            $table->decimal('harga', 10, 2);
            $table->timestamps();

            // Foreign keys
            $table->foreign('purchase_order_id')->references('id')->on('purchase_order')->onDelete('cascade');
            $table->foreign('barang_id')->references('id')->on('barang')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Drop purchase_order_items table first due to foreign key constraints
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_order');
    }
};
