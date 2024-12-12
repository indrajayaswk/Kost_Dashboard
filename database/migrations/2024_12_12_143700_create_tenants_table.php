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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id(); // Equivalent to AUTO_INCREMENT primary key
            $table->string('name', 255); // Matches varchar(255)
            $table->string('phone', 15); // Matches varchar(15)
            $table->string('ktp', 255); // Matches varchar(255)
            $table->decimal('dp', 10, 2); // Matches decimal(10,2)
            $table->date('start_date'); // Translated from 'tanggal_masuk'
            $table->date('end_date')->nullable(); // Translated from 'tanggal_keluar'
            $table->text('note')->nullable(); // Matches nullable text
            $table->timestamps(); // Includes created_at and updated_at with appropriate defaults
        });
    }

    public function down()
    {
        Schema::dropIfExists('tenants'); // Drop the 'tenants' table
    }
};
