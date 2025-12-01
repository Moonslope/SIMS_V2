<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    protected $fillable = [
        'fee_name',
        'amount',
        'program_type_id',
        'grade_level_id',
         'is_active',
    ];

    public function programType()
    {
        return $this->belongsTo(ProgramType::class);
    }

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function billingItems()
    {
        return $this->hasMany(BillingItem::class);
    }
}
