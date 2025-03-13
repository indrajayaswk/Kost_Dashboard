<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('komplains');
        Schema::dropIfExists('meterans');
        Schema::dropIfExists('penghunis');
        Schema::dropIfExists('statistiks');
        Schema::dropIfExists('kamars');
        Schema::dropIfExists('bot_sessions');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // If you ever need to rollback, you can recreate the tables here
        Schema::create('komplains', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('meterans', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('penghunis', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('statistiks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('kamars', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('bot_sessions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }
};

