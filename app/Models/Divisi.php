<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    protected $fillable = [
        'name',
        'code',
        'rfid_uid',
        'rfid_bound_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'rfid_bound_at' => 'datetime',
            'is_active'     => 'boolean',
        ];
    }

    public function isBound(): bool
    {
        return !is_null($this->rfid_uid);
    }
}
