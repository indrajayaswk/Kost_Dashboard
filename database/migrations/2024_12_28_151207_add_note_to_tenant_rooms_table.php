<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;  // Import the DB facade

class AddNoteToTenantRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Adding the note column using raw SQL
        Schema::table('tenant_rooms', function (Blueprint $table) {
            $table->string('note')->nullable();
        });

        // Reorder columns with raw SQL to place `note` before `created_at`
        DB::statement('ALTER TABLE tenant_rooms MODIFY note VARCHAR(255) AFTER status');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenant_rooms', function (Blueprint $table) {
            $table->dropColumn('note');
        });
    }
}
