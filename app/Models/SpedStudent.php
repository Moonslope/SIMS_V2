<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpedStudent extends Model
{
    protected $fillable = [
        'student_id',
        'type_of_disability',
        'date_of_diagnosis',
        'cause_of_disability'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
