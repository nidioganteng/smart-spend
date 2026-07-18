<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\AuditLog::with(['user', 'divisi', 'vendor', 'transaction', 'budgetPlan'])
            ->latest();

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->filled('divisi_id')) {
            $query->where('divisi_id', $request->divisi_id);
        }

        $logs   = $query->paginate(20)->withQueryString();
        $divisis = \App\Models\Divisi::orderBy('name')->get();

        return view('audit-log.index', compact('logs', 'divisis'));
    }
}
