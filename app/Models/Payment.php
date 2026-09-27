<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Payment extends Model { 
    protected $fillable = [
        "membership_id", "payment_method_id", "date", "amount", 
        "pending_balance", "voucher_number", "billing_name", 
        "billing_id_card", "billing_address", "cash_shift_id", "is_verified"
    ];
    protected $casts = [
        'is_verified' => 'boolean',
    ];
    public function membership() { return $this->belongsTo(Membership::class); }
    public function paymentMethod() { return $this->belongsTo(PaymentMethod::class); } 
    public function cashShift() { return $this->belongsTo(CashShift::class); }
}