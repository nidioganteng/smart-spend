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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('divisi_id')->constrained('divisis');
            $table->foreignId('vendor_id')->constrained('vendors');
            $table->foreignId('budget_plan_id')->nullable()->constrained('budget_plans');
            $table->foreignId('budget_plan_item_id')->nullable()->constrained('budget_plan_items');
            $table->string('rfid_uid');
            $table->text('qr_token_used');
            $table->string('description');
            $table->string('category');
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['approved', 'pending', 'rejected'])->default('pending');
            $table->integer('risk_score')->default(10);
            $table->enum('risk_level', ['low', 'medium', 'high'])->default('low');
            $table->json('risk_indicators');
            $table->string('validation_layer_failed')->nullable();
            $table->string('system_notes')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('reviewer_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
