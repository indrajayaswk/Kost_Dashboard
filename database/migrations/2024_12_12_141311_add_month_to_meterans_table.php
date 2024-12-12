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
        Schema::table('meterans', function (Blueprint $table) {
            // Add the 'month' column
            $table->string('month')->after('meteran')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meterans', function (Blueprint $table) {
            // Drop the 'month' column if the migration is rolled back
            $table->dropColumn('month');
        });
    }
};
