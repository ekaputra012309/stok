<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Create the satuan table
        Schema::create('satuan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Add user_id column
            $table->string('name'); // Adjust as necessary
            $table->timestamps();

            // Optional: Add foreign key constraint if users table exists
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Create the barang table
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Add user_id column
            $table->string('deskripsi'); // Adjust as necessary
            $table->string('part_number')->unique(); // Add custom code column, make it unique
            $table->integer('stok')->default(0);
            $table->integer('limit')->default(0);
            $table->foreignId('satuan_id')->constrained('satuan')->onDelete('cascade'); // Foreign key referencing satuan
            $table->timestamps();

            // Optional: Add foreign key constraint if users table exists
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop the barang table first due to foreign key constraint
        Schema::dropIfExists('barang');
        Schema::dropIfExists('satuan');
    }
};
