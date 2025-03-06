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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('uuid')->primary()->unique()->default(DB::raw('(UUID())'));
            $table->uuid('user_uuid');
            $table->uuid('company_uuid');
            $table->uuid('address_uuid')->nullable();
            $table->string('status')->default('pending');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->string('payment_method')->default('cash');
            $table->string('payment_status')->default('pending');
            $table->json('items_snapshot');
            $table->text('notes')->nullable();
            $table->timestamp('delivery_time')->nullable();
            $table->uuid('courier_uuid')->nullable();
            $table->timestamps();

            $table->foreign('user_uuid')->references('uuid')->on('users')->onDelete('cascade');
            $table->foreign('company_uuid')->references('uuid')->on('companies')->onDelete('cascade');
            $table->foreign('address_uuid')->references('uuid')->on('addresses')->onDelete('set null');
            $table->foreign('courier_uuid')->references('uuid')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
