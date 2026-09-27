<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyPass extends Model
{
    protected $fillable = ['guest_name', 'amount', 'payment_method_id'];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
