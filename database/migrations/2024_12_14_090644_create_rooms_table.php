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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('room_number'); // Room number, e.g., A1, B2, S3
            $table->string('room_type'); // Room type, e.g., A, B, S
            $table->enum('room_status', ['available', 'occupied'])->default('available'); // Room status
            $table->decimal('room_price', 10, 2); // Room price
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
