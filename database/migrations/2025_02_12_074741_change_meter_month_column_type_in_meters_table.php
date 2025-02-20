<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('meters', function (Blueprint $table) {
            $table->dateTime('meter_month')->change();
        });
    }

    public function down()
    {
        Schema::table('meters', function (Blueprint $table) {
            $table->date('meter_month')->change(); // Revert back if needed
        });
    }
};

