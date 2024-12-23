<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMetersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meters', function (Blueprint $table) {
            // Add foreign key for tenant_room_id if it doesn't exist
            if (!Schema::hasColumn('meters', 'tenant_room_id')) {
                $table->unsignedBigInteger('tenant_room_id')->after('id');
                $table->foreign('tenant_room_id')
                      ->references('id')
                      ->on('tenant_rooms')
                      ->onDelete('cascade');
            }

            // Add new columns if they don't exist
            if (!Schema::hasColumn('meters', 'kwh_number')) {
                $table->integer('kwh_number')->after('tenant_room_id');
            }

            if (!Schema::hasColumn('meters', 'total_kwh')) {
                $table->float('total_kwh')->after('kwh_number')->default(0);
            }

            if (!Schema::hasColumn('meters', 'total_price')) {
                $table->float('total_price')->after('total_kwh')->default(0);
            }

            if (!Schema::hasColumn('meters', 'price_per_kwh')) {
                $table->float('price_per_kwh')->after('total_price')->default(0);
            }

            if (!Schema::hasColumn('meters', 'status')) {
                $table->enum('status', ['paid', 'unpaid'])->after('price_per_kwh')->default('unpaid');
            }

            if (!Schema::hasColumn('meters', 'pay_proof')) {
                $table->string('pay_proof')->nullable()->after('status');
            }

            if (!Schema::hasColumn('meters', 'month')) {
                $table->date('month')->after('pay_proof');
            }

            // Make sure timestamps and soft deletes are present
            if (!Schema::hasColumns('meters', ['created_at', 'updated_at'])) {
                $table->timestamps(); // Adds `created_at` and `updated_at`
            }

            if (!Schema::hasColumn('meters', 'deleted_at')) {
                $table->softDeletes(); // Adds `deleted_at`
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meters', function (Blueprint $table) {
            // Drop foreign key and column for tenant_room_id
            if (Schema::hasColumn('meters', 'tenant_room_id')) {
                $table->dropForeign(['tenant_room_id']);
                $table->dropColumn('tenant_room_id');
            }

            // Drop the newly added columns
            if (Schema::hasColumn('meters', 'kwh_number')) {
                $table->dropColumn('kwh_number');
            }

            if (Schema::hasColumn('meters', 'total_kwh')) {
                $table->dropColumn('total_kwh');
            }

            if (Schema::hasColumn('meters', 'total_price')) {
                $table->dropColumn('total_price');
            }

            if (Schema::hasColumn('meters', 'price_per_kwh')) {
                $table->dropColumn('price_per_kwh');
            }

            if (Schema::hasColumn('meters', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('meters', 'pay_proof')) {
                $table->dropColumn('pay_proof');
            }

            if (Schema::hasColumn('meters', 'month')) {
                $table->dropColumn('month');
            }

            // Drop timestamps and soft deletes
            $table->dropTimestamps();
            $table->dropSoftDeletes();
        });
    }
}
