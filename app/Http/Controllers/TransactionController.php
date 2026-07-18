<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\AuditLogService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function __construct(private TransactionService $service) {}

    public function index()
    {
        $transactions = Transaction::with(['divisi', 'vendor'])
            ->latest()
            ->paginate(15);

        return view('transaction.index', compact('transactions'));
    }

    public function create()
    {
        return view('transaction.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'rfid_uid'    => 'required|string',
            'qr_token'    => 'required|string',
            'description' => 'required|string|max:200',
            'category'    => 'required|string|max:100',
            'amount'      => 'required|numeric|min:1000',
        ]);

        $result = $this->service->process($request->only([
            'rfid_uid', 'qr_token', 'description', 'category', 'amount',
        ]));

        $transaction = Transaction::create([
            'divisi_id'               => $result['divisi']?->id,
            'vendor_id'               => $result['vendor']?->id,
            'budget_plan_id'          => $result['budget_plan']?->id,
            'budget_plan_item_id'     => $result['budget_plan_item']?->id,
            'rfid_uid'                => $result['rfid_uid'],
            'qr_token_used'           => $result['qr_token_used'],
            'description'             => $result['description'],
            'category'                => $result['category'],
            'amount'                  => $result['amount'],
            'status'                  => $result['status'],
            'risk_score'              => $result['risk_score'],
            'risk_level'              => $result['risk_level'],
            'risk_indicators'         => $result['risk_indicators'],
            'validation_layer_failed' => $result['validation_layer_failed'],
            'system_notes'            => $result['system_notes'],
        ]);

        AuditLogService::log(
            'transaction_' . $result['status'],
            "Transaksi {$result['status']}: {$result['description']} — Rp " . number_format($result['amount'], 0, ',', '.'),
            [
                'divisi_id'      => $result['divisi']?->id,
                'vendor_id'      => $result['vendor']?->id,
                'transaction_id' => $transaction->id,
                'budget_plan_id' => $result['budget_plan']?->id,
            ],
            ['risk_score' => $result['risk_score'], 'risk_level' => $result['risk_level']]
        );

        return redirect()->route('transaction.show', $transaction)
                         ->with('success', 'Transaksi diproses.');
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['divisi', 'vendor', 'budgetPlan', 'budgetPlanItem', 'reviewedBy']);
        return view('transaction.show', compact('transaction'));
    }

    public function review(Request $request, Transaction $transaction)
    {
        abort_unless($transaction->isPending(), 403, 'Transaksi ini tidak dalam status pending.');

        $request->validate([
            'action'        => 'required|in:approve,reject',
            'reviewer_notes'=> 'required|string|max:500',
        ]);

        DB::transaction(function () use ($request, $transaction) {
            if ($request->action === 'approve') {
                $wallet = $transaction->divisi->wallet;
                abort_unless($wallet->hasSufficientBalance((float) $transaction->amount), 422, 'Saldo wallet tidak mencukupi.');

                $wallet->deduct((float) $transaction->amount);

                if ($transaction->budgetPlan) {
                    $transaction->budgetPlan->increment('realized_amount', (float) $transaction->amount);
                }
                if ($transaction->budgetPlanItem) {
                    $transaction->budgetPlanItem->increment('realized_amount', (float) $transaction->amount);
                }
            }

            $transaction->update([
                'status'         => $request->action === 'approve' ? 'approved' : 'rejected',
                'reviewed_by'    => Auth::id(),
                'reviewed_at'    => Carbon::now(),
                'reviewer_notes' => $request->reviewer_notes,
            ]);

            AuditLogService::log(
                'transaction_review_' . $request->action,
                "Review manual transaksi #{$transaction->id}: " . ($request->action === 'approve' ? 'disetujui' : 'ditolak'),
                ['divisi_id' => $transaction->divisi_id, 'transaction_id' => $transaction->id],
                ['reviewer_notes' => $request->reviewer_notes]
            );
        });

        $message = $request->action === 'approve'
            ? 'Transaksi disetujui dan dana telah didebit.'
            : 'Transaksi ditolak.';

        return redirect()->route('transaction.show', $transaction)->with('success', $message);
    }

    public function pending()
    {
        $transactions = Transaction::with(['divisi', 'vendor'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        return view('transaction.pending', compact('transactions'));
    }
}
