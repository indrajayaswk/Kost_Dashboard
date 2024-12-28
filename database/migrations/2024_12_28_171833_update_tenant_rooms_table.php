<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTenantRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tenant_rooms', function (Blueprint $table) {
            // Rename tenant_id to primary_tenant_id
            $table->renameColumn('tenant_id', 'primary_tenant_id');
            
            // Add secondary_tenant_id column
            $table->unsignedBigInteger('secondary_tenant_id')->nullable()->after('primary_tenant_id');
            
            // Add foreign key constraints
            $table->foreign('primary_tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('secondary_tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenant_rooms', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['primary_tenant_id']);
            $table->dropForeign(['secondary_tenant_id']);
            
            // Remove secondary_tenant_id column
            $table->dropColumn('secondary_tenant_id');
            
            // Rename primary_tenant_id back to tenant_id
            $table->renameColumn('primary_tenant_id', 'tenant_id');
        });
    }
}
