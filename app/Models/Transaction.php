<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'divisi_id', 'vendor_id', 'budget_plan_id', 'budget_plan_item_id',
        'rfid_uid', 'qr_token_used', 'description', 'category', 'amount',
        'status', 'risk_score', 'risk_level', 'risk_indicators',
        'validation_layer_failed', 'system_notes',
        'reviewed_by', 'reviewed_at', 'reviewer_notes',
    ];

    protected function casts(): array
    {
        return [
            'risk_indicators' => 'array',
            'reviewed_at'     => 'datetime',
            'amount'          => 'decimal:2',
        ];
    }

    public function divisi()       { return $this->belongsTo(Divisi::class); }
    public function vendor()       { return $this->belongsTo(Vendor::class); }
    public function budgetPlan()   { return $this->belongsTo(BudgetPlan::class); }
    public function budgetPlanItem() { return $this->belongsTo(BudgetPlanItem::class); }
    public function reviewedBy()   { return $this->belongsTo(User::class, 'reviewed_by'); }

    public function isApproved(): bool { return $this->status === 'approved'; }
    public function isPending(): bool  { return $this->status === 'pending'; }
    public function isRejected(): bool { return $this->status === 'rejected'; }
}
