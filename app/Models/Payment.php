<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'billing_id',
        'billing_item_id',
        'academic_year_id',
        'amount_paid',
        'payment_date',
        'reference_number',
        'processedBy',
        'description',
        'purpose'
    ];

    public function billing()
    {
        return $this->belongsTo(Billing::class);
    }

    public function billingItem()
    {
        return $this->belongsTo(BillingItem::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function processedByUser()
    {
        return $this->belongsTo(User::class, 'processedBy');
    }
}
