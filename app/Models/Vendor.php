<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'name',
        'code',
        'category',
        'qr_token',
        'qr_expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'qr_expires_at' => 'datetime',
            'is_active'     => 'boolean',
        ];
    }

    public function isQrValid(): bool
    {
        return !is_null($this->qr_token) && now()->lt($this->qr_expires_at);
    }
}
