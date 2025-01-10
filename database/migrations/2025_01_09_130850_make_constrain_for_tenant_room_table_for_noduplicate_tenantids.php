<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tenant_rooms', function (Blueprint $table) {
            $table->unique(['primary_tenant_id', 'status','deleted_at'], 'unique_primary_tenant_status');
            $table->unique(['secondary_tenant_id', 'status','deleted_at'], 'unique_secondary_tenant_status');
        });
    }

    public function down()
    {
        Schema::table('tenant_rooms', function (Blueprint $table) {
            $table->dropUnique('unique_primary_tenant_status');
            $table->dropUnique('unique_secondary_tenant_status');
        });
    }
};
