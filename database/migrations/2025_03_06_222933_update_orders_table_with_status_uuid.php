<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop the existing status column
            $table->dropColumn('status');

            // Add the new status_uuid column
            $table->uuid('status_uuid')->nullable()->after('address_uuid');

            // Add foreign key constraint
            $table->foreign('status_uuid')->references('uuid')->on('statuses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop the foreign key first
            $table->dropForeign(['status_uuid']);

            // Drop the status_uuid column
            $table->dropColumn('status_uuid');

            // Re-add the original status column
            $table->string('status')->default('pending')->after('address_uuid');
        });
    }
};
