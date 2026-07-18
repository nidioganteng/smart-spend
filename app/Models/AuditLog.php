<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'event_type', 'user_id', 'divisi_id', 'vendor_id',
        'transaction_id', 'budget_plan_id', 'description', 'metadata',
    ];

    protected function casts(): array
    {
        return ['metadata' => 'array'];
    }

    public function user()        { return $this->belongsTo(User::class); }
    public function divisi()      { return $this->belongsTo(Divisi::class); }
    public function vendor()      { return $this->belongsTo(Vendor::class); }
    public function transaction() { return $this->belongsTo(Transaction::class); }
    public function budgetPlan()  { return $this->belongsTo(BudgetPlan::class); }
}
