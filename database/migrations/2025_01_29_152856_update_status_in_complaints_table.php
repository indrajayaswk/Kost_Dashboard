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
        // Modify the 'status' column in the 'complaints' table
        Schema::table('complaints', function (Blueprint $table) {
            // Change the 'status' column to the new enum values
            $table->enum('status', ['pending', 'completed'])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the 'status' column modification (restore previous enum values)
        Schema::table('complaints', function (Blueprint $table) {
            $table->enum('status', ['unread', 'read', 'finished'])->default('unread')->change();
        });
    }
};
