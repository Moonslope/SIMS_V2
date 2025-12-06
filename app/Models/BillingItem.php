<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingItem extends Model
{
    protected $fillable = [
        'billing_id',
        'fee_structure_id',
        'amount',
        'amount_paid',
        'status',
        'payment_date'
    ];
        
    protected $casts = [
        'payment_date' => 'date',
    ];

    public function billing()
    {
        return $this->belongsTo(Billing::class);
    }

    public function feeStructure()
    {
        return $this->belongsTo(FeeStructure::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getRemainingBalanceAttribute()
    {
        return $this->amount - $this->amount_paid;
    }
}
