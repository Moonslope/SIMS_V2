<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $fillable = [
        'enrollment_id',
        'total_amount',
        'status',
        'created_date'
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function billingItems()
    {
        return $this->hasMany(BillingItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
