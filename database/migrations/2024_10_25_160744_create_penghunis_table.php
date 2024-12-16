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
    Schema::create('penghunis', function (Blueprint $table) {
        $table->id(); // Equivalent to AUTO_INCREMENT primary key
        $table->string('nama', 255); // Matches varchar(255)
        $table->string('telphon', 15); // Matches varchar(15)
        $table->string('ktp', 255); // Matches varchar(255)
        $table->decimal('dp', 10, 2); // Matches decimal(10,2)
        $table->date('tanggal_masuk'); // Matches date
        $table->date('tanggal_keluar')->nullable(); // Matches nullable date
        $table->text('note')->nullable(); // Matches nullable text
        $table->timestamps(); // Includes created_at and updated_at with appropriate defaults
    });
}

public function down()
{
    Schema::dropIfExists('penghunis');
}

};
