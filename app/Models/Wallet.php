<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = ['divisi_id', 'balance'];

    protected function casts(): array
    {
        return ['balance' => 'decimal:2'];
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    public function topUp(float $amount): void
    {
        $this->increment('balance', $amount);
    }

    public function deduct(float $amount): void
    {
        $this->decrement('balance', $amount);
    }

    public function hasSufficientBalance(float $amount): bool
    {
        return $this->balance >= $amount;
    }
}
