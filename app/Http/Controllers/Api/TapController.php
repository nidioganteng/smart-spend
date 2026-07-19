<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TapController extends Controller
{
    public function __construct(private \App\Services\TransactionService $service) {}

    public function cardCheck(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate(['rfid_uid' => 'required|string']);

        $divisi = \App\Models\Divisi::where('rfid_uid', $request->rfid_uid)
            ->where('is_active', true)
            ->with('wallet')
            ->first();

        if (!$divisi) {
            return response()->json([
                'valid'   => false,
                'message' => 'Kartu tidak dikenali atau tidak aktif',
            ], 404);
        }

        return response()->json([
            'valid'          => true,
            'divisi'         => $divisi->name,
            'wallet_balance' => $divisi->wallet?->balance ?? 0,
            'message'        => 'Kartu valid - ' . $divisi->name,
        ]);
    }

    public function tap(Request $request): \Illuminate\Http\JsonResponse
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

        $transaction = \App\Models\Transaction::create([
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

        return response()->json([
            'transaction_id'  => $transaction->id,
            'status'          => $result['status'],
            'risk_score'      => $result['risk_score'],
            'risk_level'      => $result['risk_level'],
            'system_notes'    => $result['system_notes'],
            'divisi'          => $result['divisi']?->name,
            'vendor'          => $result['vendor']?->name,
        ], 201);
    }
}
