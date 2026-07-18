<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetPlanItem extends Model
{
    protected $fillable = [
        'budget_plan_id', 'category', 'description', 'amount', 'realized_amount',
    ];

    protected function casts(): array
    {
        return [
            'amount'          => 'decimal:2',
            'realized_amount' => 'decimal:2',
        ];
    }

    public function budgetPlan()
    {
        return $this->belongsTo(BudgetPlan::class);
    }

    public function remainingCeiling(): float
    {
        return (float) $this->amount - (float) $this->realized_amount;
    }
}
