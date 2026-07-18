<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    public static function log(
        string $eventType,
        string $description,
        array $relations = [],
        array $metadata = []
    ): void {
        AuditLog::create([
            'event_type'     => $eventType,
            'user_id'        => $relations['user_id'] ?? Auth::id(),
            'divisi_id'      => $relations['divisi_id'] ?? null,
            'vendor_id'      => $relations['vendor_id'] ?? null,
            'transaction_id' => $relations['transaction_id'] ?? null,
            'budget_plan_id' => $relations['budget_plan_id'] ?? null,
            'description'    => $description,
            'metadata'       => empty($metadata) ? null : $metadata,
        ]);
    }
}
