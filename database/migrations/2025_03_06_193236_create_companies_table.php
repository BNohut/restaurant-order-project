<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->uuid('uuid')->primary()->unique()->default(DB::raw('(UUID())'));
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('tax_number')->nullable();
            $table->uuid('owner_uuid');
            $table->uuid('address_uuid')->nullable();
            $table->json('business_hours')->nullable();
            $table->float('delivery_radius')->nullable();
            $table->float('delivery_fee')->default(0);
            $table->float('minimum_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->foreign('owner_uuid')->references('uuid')->on('users')->onDelete('cascade');
            $table->foreign('address_uuid')->references('uuid')->on('addresses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
