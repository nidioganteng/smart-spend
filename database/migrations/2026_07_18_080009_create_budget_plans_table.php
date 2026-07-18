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
        Schema::create('budget_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('divisi_id')->constrained('divisis');
            $table->foreignId('submitted_by')->constrained('users');
            $table->string('title');
            $table->string('period');
            $table->enum('status', ['draft', 'pending_finance', 'revision', 'pending_leader', 'approved', 'rejected'])->default('draft');
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('realized_amount', 15, 2)->default(0);
            $table->foreignId('finance_reviewed_by')->nullable()->constrained('users');
            $table->timestamp('finance_reviewed_at')->nullable();
            $table->text('finance_notes')->nullable();
            $table->foreignId('leader_approved_by')->nullable()->constrained('users');
            $table->timestamp('leader_approved_at')->nullable();
            $table->text('leader_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_plans');
    }
};
