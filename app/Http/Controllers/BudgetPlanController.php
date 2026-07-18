<?php

namespace App\Http\Controllers;

use App\Models\BudgetPlan;
use App\Models\Divisi;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BudgetPlanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = BudgetPlan::with(['divisi', 'submittedBy'])->latest();

        if ($user->isHeadDivision()) {
            $query->where('submitted_by', $user->id);
        }

        $budgetPlans = $query->paginate(10);

        return view('budget-plan.index', compact('budgetPlans'));
    }

    public function create()
    {
        $divisis = Divisi::where('is_active', true)->get();
        return view('budget-plan.create', compact('divisis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'divisi_id'          => 'required|exists:divisis,id',
            'title'              => 'required|string|max:200',
            'period'             => 'required|string|max:50',
            'items'              => 'required|array|min:1',
            'items.*.category'   => 'required|string|max:100',
            'items.*.description'=> 'required|string|max:200',
            'items.*.amount'     => 'required|numeric|min:1000',
        ]);

        DB::transaction(function () use ($request) {
            $totalAmount = collect($request->items)->sum('amount');

            $bp = BudgetPlan::create([
                'divisi_id'    => $request->divisi_id,
                'submitted_by' => Auth::id(),
                'title'        => $request->title,
                'period'       => $request->period,
                'status'       => 'draft',
                'total_amount' => $totalAmount,
            ]);

            foreach ($request->items as $item) {
                $bp->items()->create([
                    'category'    => $item['category'],
                    'description' => $item['description'],
                    'amount'      => $item['amount'],
                ]);
            }
        });

        return redirect()->route('budget-plan.index')->with('success', 'Budget Plan berhasil dibuat.');
    }

    public function show(BudgetPlan $budgetPlan)
    {
        $budgetPlan->load(['divisi.wallet', 'submittedBy', 'items', 'financeReviewedBy', 'leaderApprovedBy']);
        return view('budget-plan.show', compact('budgetPlan'));
    }

    public function edit(BudgetPlan $budgetPlan)
    {
        abort_unless($budgetPlan->isDraft() || $budgetPlan->isRevision(), 403, 'Budget Plan ini tidak dapat diedit.');

        $divisis = Divisi::where('is_active', true)->get();
        $budgetPlan->load('items');

        return view('budget-plan.edit', compact('budgetPlan', 'divisis'));
    }

    public function update(Request $request, BudgetPlan $budgetPlan)
    {
        abort_unless($budgetPlan->isDraft() || $budgetPlan->isRevision(), 403);

        $request->validate([
            'title'              => 'required|string|max:200',
            'period'             => 'required|string|max:50',
            'items'              => 'required|array|min:1',
            'items.*.category'   => 'required|string|max:100',
            'items.*.description'=> 'required|string|max:200',
            'items.*.amount'     => 'required|numeric|min:1000',
        ]);

        DB::transaction(function () use ($request, $budgetPlan) {
            $totalAmount = collect($request->items)->sum('amount');

            $budgetPlan->update([
                'title'        => $request->title,
                'period'       => $request->period,
                'total_amount' => $totalAmount,
                'status'       => 'draft',
            ]);

            $budgetPlan->items()->delete();

            foreach ($request->items as $item) {
                $budgetPlan->items()->create([
                    'category'    => $item['category'],
                    'description' => $item['description'],
                    'amount'      => $item['amount'],
                ]);
            }
        });

        return redirect()->route('budget-plan.show', $budgetPlan)->with('success', 'Budget Plan berhasil diperbarui.');
    }

    public function destroy(BudgetPlan $budgetPlan)
    {
        abort_unless($budgetPlan->isDraft(), 403, 'Hanya draft yang bisa dihapus.');
        $budgetPlan->delete();
        return redirect()->route('budget-plan.index')->with('success', 'Budget Plan berhasil dihapus.');
    }

    public function submit(BudgetPlan $budgetPlan)
    {
        abort_unless($budgetPlan->isDraft() || $budgetPlan->isRevision(), 403);

        $budgetPlan->update(['status' => 'pending_finance']);

        AuditLogService::log(
            'bp_submitted',
            "Budget Plan \"{$budgetPlan->title}\" diajukan ke Finance Staff.",
            ['divisi_id' => $budgetPlan->divisi_id, 'budget_plan_id' => $budgetPlan->id]
        );

        return redirect()->route('budget-plan.show', $budgetPlan)->with('success', 'Budget Plan berhasil diajukan ke Finance Staff.');
    }

    public function financeReview(Request $request, BudgetPlan $budgetPlan)
    {
        abort_unless($budgetPlan->status === 'pending_finance', 403);

        $request->validate([
            'action' => 'required|in:approve,revision',
            'notes'  => 'nullable|string|max:500',
        ]);

        $newStatus = $request->action === 'approve' ? 'pending_leader' : 'revision';

        $budgetPlan->update([
            'status'               => $newStatus,
            'finance_reviewed_by'  => Auth::id(),
            'finance_reviewed_at'  => Carbon::now(),
            'finance_notes'        => $request->notes,
        ]);

        AuditLogService::log(
            'bp_finance_' . $request->action,
            "Finance review BP \"{$budgetPlan->title}\": " . ($request->action === 'approve' ? 'diteruskan ke Pimpinan' : 'dikembalikan untuk revisi'),
            ['divisi_id' => $budgetPlan->divisi_id, 'budget_plan_id' => $budgetPlan->id],
            ['notes' => $request->notes]
        );

        $message = $request->action === 'approve'
            ? 'Budget Plan disetujui, diteruskan ke Pimpinan.'
            : 'Budget Plan dikembalikan untuk revisi.';

        return redirect()->route('budget-plan.show', $budgetPlan)->with('success', $message);
    }

    public function leaderReview(Request $request, BudgetPlan $budgetPlan)
    {
        abort_unless($budgetPlan->status === 'pending_leader', 403);

        $request->validate([
            'action' => 'required|in:approve,reject',
            'notes'  => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $budgetPlan) {
            $newStatus = $request->action === 'approve' ? 'approved' : 'rejected';

            $budgetPlan->update([
                'status'              => $newStatus,
                'leader_approved_by'  => Auth::id(),
                'leader_approved_at'  => Carbon::now(),
                'leader_notes'        => $request->notes,
            ]);

            if ($request->action === 'approve') {
                $wallet = $budgetPlan->divisi->wallet
                    ?? $budgetPlan->divisi->wallet()->create(['balance' => 0]);
                $wallet->topUp((float) $budgetPlan->total_amount);
            }

            AuditLogService::log(
                'bp_leader_' . $request->action,
                "Pimpinan " . ($request->action === 'approve' ? 'menyetujui' : 'menolak') . " BP \"{$budgetPlan->title}\"" . ($request->action === 'approve' ? " — dana Rp " . number_format($budgetPlan->total_amount, 0, ',', '.') . " dialokasikan." : '.'),
                ['divisi_id' => $budgetPlan->divisi_id, 'budget_plan_id' => $budgetPlan->id],
                ['notes' => $request->notes]
            );
        });

        $message = $request->action === 'approve'
            ? 'Budget Plan disetujui. Dana telah dialokasikan ke wallet divisi.'
            : 'Budget Plan ditolak.';

        return redirect()->route('budget-plan.show', $budgetPlan)->with('success', $message);
    }
}
