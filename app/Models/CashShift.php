<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashShift extends Model
{
    protected $fillable = [
        'user_id',
        'opening_time',
        'closing_time',
        'initial_base_cash',
        'total_system_cash',
        'total_declared_cash',
        'cash_difference',
        'total_system_transfers',
        'status',
        'observations',
        'cash_breakdown',
    ];

    protected $casts = [
        'opening_time' => 'datetime',
        'closing_time' => 'datetime',
        'initial_base_cash' => 'decimal:2',
        'total_system_cash' => 'decimal:2',
        'total_declared_cash' => 'decimal:2',
        'cash_difference' => 'decimal:2',
        'total_system_transfers' => 'decimal:2',
        'cash_breakdown' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
