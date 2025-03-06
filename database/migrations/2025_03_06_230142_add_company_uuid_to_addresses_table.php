<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            // Add company_uuid field
            $table->uuid('company_uuid')->nullable()->after('user_uuid');

            // Modify user_uuid to be nullable
            $table->uuid('user_uuid')->nullable()->change();
        });

        // Add foreign key constraint for company_uuid
        Schema::table('addresses', function (Blueprint $table) {
            $table->foreign('company_uuid')->references('uuid')->on('companies')->onDelete('cascade');
        });

        // Add check constraint to ensure at least one of user_uuid or company_uuid is not null
        DB::statement('ALTER TABLE addresses ADD CONSTRAINT check_owner CHECK (user_uuid IS NOT NULL OR company_uuid IS NOT NULL)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the check constraint first
        DB::statement('ALTER TABLE addresses DROP CONSTRAINT check_owner');

        // Drop the foreign key constraint for company_uuid
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropForeign(['company_uuid']);
        });

        // Drop the company_uuid column and make user_uuid not nullable again
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn('company_uuid');
            $table->uuid('user_uuid')->nullable(false)->change();
        });
    }
};
