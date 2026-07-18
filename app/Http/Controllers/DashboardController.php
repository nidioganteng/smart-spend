<?php

namespace App\Http\Controllers;

use App\Models\BudgetPlan;
use App\Models\Divisi;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [];

        if ($user->isAdmin()) {
            $data = [
                'total_divisi'  => Divisi::count(),
                'total_vendor'  => \App\Models\Vendor::count(),
                'total_wallet'  => Wallet::sum('balance'),
                'trx_approved'  => Transaction::where('status', 'approved')->count(),
                'trx_pending'   => Transaction::where('status', 'pending')->count(),
                'trx_rejected'  => Transaction::where('status', 'rejected')->count(),
                'recent_trx'    => Transaction::with(['divisi', 'vendor'])->latest()->limit(5)->get(),
            ];
        }

        if ($user->isHeadDivision()) {
            $myBps = BudgetPlan::where('submitted_by', $user->id);
            $myBpIds = (clone $myBps)->pluck('id');
            $data = [
                'bp_draft'      => (clone $myBps)->where('status', 'draft')->count(),
                'bp_pending'    => (clone $myBps)->whereIn('status', ['pending_finance', 'pending_leader'])->count(),
                'bp_approved'   => (clone $myBps)->where('status', 'approved')->count(),
                'total_budget'  => (clone $myBps)->where('status', 'approved')->sum('total_amount'),
                'total_realized'=> (clone $myBps)->where('status', 'approved')->sum('realized_amount'),
                'recent_trx'    => Transaction::with(['vendor'])->whereIn('budget_plan_id', $myBpIds)->latest()->limit(5)->get(),
            ];
        }

        if ($user->isFinanceStaff()) {
            $data = [
                'bp_pending_finance' => BudgetPlan::where('status', 'pending_finance')->count(),
                'trx_pending'        => Transaction::where('status', 'pending')->count(),
                'trx_approved_today' => Transaction::where('status', 'approved')->whereDate('created_at', today())->count(),
                'total_wallet'       => Wallet::sum('balance'),
                'recent_trx'         => Transaction::with(['divisi', 'vendor'])->latest()->limit(5)->get(),
            ];
        }

        if ($user->isLeader()) {
            $data = [
                'bp_pending_leader'  => BudgetPlan::where('status', 'pending_leader')->count(),
                'bp_approved_total'  => BudgetPlan::where('status', 'approved')->count(),
                'total_budget'       => BudgetPlan::where('status', 'approved')->sum('total_amount'),
                'total_realized'     => BudgetPlan::where('status', 'approved')->sum('realized_amount'),
                'total_wallet'       => Wallet::sum('balance'),
                'trx_high_risk'      => Transaction::where('risk_level', 'high')->count(),
            ];
        }

        return view('dashboard', compact('data'));
    }
}
