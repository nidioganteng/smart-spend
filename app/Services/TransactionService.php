<?php

namespace App\Services;

use App\Models\BudgetPlan;
use App\Models\BudgetPlanItem;
use App\Models\Divisi;
use App\Models\Transaction;
use App\Models\Vendor;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function process(array $data): array
    {
        $indicators = ['A' => 0, 'V' => 0, 'C' => 0, 'F' => 0, 'M' => 0];
        $validationLayerFailed = null;
        $systemNotes = [];

        // V-01: Kartu RFID valid & aktif
        $divisi = Divisi::where('rfid_uid', strtoupper($data['rfid_uid']))
                        ->where('is_active', true)
                        ->first();

        if (!$divisi) {
            $indicators['A'] = 1;
            $riskScore = min(100, 10 + 25);
            return $this->buildResult(
                null, null, null, null,
                $data, $indicators, $riskScore, 'V-01',
                'Kartu RFID tidak dikenali atau divisi tidak aktif.',
                'rejected'
            );
        }

        // V-02: Vendor QR valid & belum expired
        $vendor = Vendor::where('is_active', true)->get()->first(function ($v) use ($data) {
            return $v->qr_token === $data['qr_token'] && $v->isQrValid();
        });

        if (!$vendor) {
            $indicators['V'] = 1;
            $riskScore = min(100, 10 + 25);
            return $this->buildResult(
                $divisi, null, null, null,
                $data, $indicators, $riskScore, 'V-02',
                'QR Code vendor tidak valid atau sudah expired.',
                'rejected'
            );
        }

        // Cek F: frekuensi mencurigakan (3+ transaksi dari divisi yang sama dalam 1 jam terakhir)
        $recentCount = Transaction::where('divisi_id', $divisi->id)
                                  ->where('created_at', '>=', Carbon::now()->subHour())
                                  ->count();
        if ($recentCount >= 3) {
            $indicators['F'] = 1;
            $systemNotes[] = 'Frekuensi transaksi mencurigakan dalam 1 jam terakhir.';
        }

        // Cari BP yang approved untuk divisi ini, cocokkan kategori
        $matchedItem = null;
        $matchedBp   = null;

        $approvedBps = BudgetPlan::where('divisi_id', $divisi->id)
                                  ->where('status', 'approved')
                                  ->with('items')
                                  ->get();

        foreach ($approvedBps as $bp) {
            $item = $bp->items->first(fn($i) =>
                strtolower($i->category) === strtolower($data['category'])
            );
            if ($item) {
                $matchedItem = $item;
                $matchedBp   = $bp;
                break;
            }
        }

        // V-03: Kategori cocok dengan BP
        if (!$matchedItem) {
            $indicators['M'] = 1;
            $systemNotes[] = 'Kategori tidak cocok dengan item BP yang disetujui.';
        }

        // V-04: Saldo wallet cukup
        $wallet = $divisi->wallet ?? $divisi->wallet()->create(['balance' => 0]);
        if (!$wallet->hasSufficientBalance((float) $data['amount'])) {
            $indicators['C'] = 1;
            $riskScore = $this->calculateScore($indicators);
            return $this->buildResult(
                $divisi, $vendor, $matchedBp, $matchedItem,
                $data, $indicators, $riskScore, 'V-04',
                'Saldo wallet tidak mencukupi.',
                'rejected'
            );
        }

        // V-05: Tidak melebihi ceiling BP
        $exceedsCeiling = false;
        if ($matchedItem) {
            $remaining = $matchedItem->remainingCeiling();
            if ((float) $data['amount'] > $remaining) {
                $indicators['C'] = 1;
                $exceedsCeiling  = true;
                $systemNotes[]   = 'Nominal melebihi sisa ceiling item BP.';
            }
        }

        // Hitung risk score
        $riskScore = $this->calculateScore($indicators);
        $riskLevel = $this->classifyRisk($riskScore);

        // Tentukan status akhir
        if (!$matchedItem || $exceedsCeiling || $riskLevel === 'high') {
            $status = 'pending';
            if ($riskLevel === 'high') {
                $systemNotes[] = 'Risk score tinggi, membutuhkan review manual.';
            }
        } else {
            $status = 'approved';
        }

        // Jika approved: debit wallet dan update realisasi BP
        if ($status === 'approved') {
            DB::transaction(function () use ($wallet, $data, $matchedBp, $matchedItem) {
                $wallet->deduct((float) $data['amount']);
                $matchedBp->increment('realized_amount', (float) $data['amount']);
                $matchedItem->increment('realized_amount', (float) $data['amount']);
            });
        }

        return $this->buildResult(
            $divisi, $vendor, $matchedBp, $matchedItem,
            $data, $indicators, $riskScore,
            null,
            implode(' ', $systemNotes) ?: null,
            $status
        );
    }

    private function calculateScore(array $indicators): int
    {
        return min(100,
            10
            + ($indicators['A'] * 25)
            + ($indicators['V'] * 25)
            + ($indicators['C'] * 20)
            + ($indicators['F'] * 15)
            + ($indicators['M'] * 15)
        );
    }

    private function classifyRisk(int $score): string
    {
        if ($score <= 30) return 'low';
        if ($score <= 60) return 'medium';
        return 'high';
    }

    private function buildResult(
        ?Divisi $divisi,
        ?Vendor $vendor,
        ?BudgetPlan $bp,
        ?BudgetPlanItem $item,
        array $data,
        array $indicators,
        int $riskScore,
        ?string $layerFailed,
        ?string $systemNotes,
        string $status
    ): array {
        return [
            'divisi'                 => $divisi,
            'vendor'                 => $vendor,
            'budget_plan'            => $bp,
            'budget_plan_item'       => $item,
            'rfid_uid'               => strtoupper($data['rfid_uid']),
            'qr_token_used'          => $data['qr_token'],
            'description'            => $data['description'],
            'category'               => $data['category'],
            'amount'                 => $data['amount'],
            'status'                 => $status,
            'risk_score'             => $riskScore,
            'risk_level'             => $this->classifyRisk($riskScore),
            'risk_indicators'        => $indicators,
            'validation_layer_failed'=> $layerFailed,
            'system_notes'           => $systemNotes,
        ];
    }
}
