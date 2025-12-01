<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnrollmentSchedule extends Model
{
    protected $fillable = [
        'enrollment_id',
        'schedule_id'
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
