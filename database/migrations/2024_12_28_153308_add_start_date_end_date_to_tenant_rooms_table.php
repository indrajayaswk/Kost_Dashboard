<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;


class AddStartDateEndDateToTenantRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Reorder columns with raw SQL and add start_date and end_date
        DB::statement('ALTER TABLE tenant_rooms ADD COLUMN start_date DATE AFTER status');
        DB::statement('ALTER TABLE tenant_rooms ADD COLUMN end_date DATE AFTER start_date');
        DB::statement('ALTER TABLE tenant_rooms MODIFY note VARCHAR(255) AFTER end_date');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Rollback the changes (remove the start_date, end_date, and restore the note position)
        DB::statement('ALTER TABLE tenant_rooms DROP COLUMN start_date');
        DB::statement('ALTER TABLE tenant_rooms DROP COLUMN end_date');
    }
}
