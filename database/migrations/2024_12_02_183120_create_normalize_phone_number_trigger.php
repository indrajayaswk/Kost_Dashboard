<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create the trigger using raw SQL
        DB::unprepared("
            CREATE TRIGGER normalize_phone_number_penghuni
            BEFORE INSERT ON penghunis
            FOR EACH ROW
            BEGIN
                -- Remove non-numeric characters from the phone number
                SET NEW.telphon = REGEXP_REPLACE(NEW.telphon, '[^0-9]', '');

                -- If the phone number starts with '0', replace it with '62'
                IF LEFT(NEW.telphon, 1) = '0' THEN
                    SET NEW.telphon = CONCAT('62', SUBSTRING(NEW.telphon, 2));
                END IF;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the trigger using raw SQL
        DB::unprepared("DROP TRIGGER IF EXISTS normalize_phone_number_penghuni;");
    }
};
