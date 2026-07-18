<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetPlan extends Model
{
    protected $fillable = [
        'divisi_id', 'submitted_by', 'title', 'period', 'status',
        'total_amount', 'realized_amount',
        'finance_reviewed_by', 'finance_reviewed_at', 'finance_notes',
        'leader_approved_by', 'leader_approved_at', 'leader_notes',
    ];

    protected function casts(): array
    {
        return [
            'finance_reviewed_at' => 'datetime',
            'leader_approved_at'  => 'datetime',
            'total_amount'        => 'decimal:2',
            'realized_amount'     => 'decimal:2',
        ];
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function financeReviewedBy()
    {
        return $this->belongsTo(User::class, 'finance_reviewed_by');
    }

    public function leaderApprovedBy()
    {
        return $this->belongsTo(User::class, 'leader_approved_by');
    }

    public function items()
    {
        return $this->hasMany(BudgetPlanItem::class);
    }

    public function isDraft(): bool       { return $this->status === 'draft'; }
    public function isRevision(): bool    { return $this->status === 'revision'; }
    public function isApproved(): bool    { return $this->status === 'approved'; }
    public function isRejected(): bool    { return $this->status === 'rejected'; }

    public function remainingBudget(): float
    {
        return (float) $this->total_amount - (float) $this->realized_amount;
    }
}
