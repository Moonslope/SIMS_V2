<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'subject_name',
        'description',
        'grade_level_id',
        'is_active',
    ];

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function enrollmentSubjects()
    {
        return $this->hasMany(EnrollmentSubject::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
