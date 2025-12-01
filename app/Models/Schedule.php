<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'section_id',
        'academic_year_id',
        'subject_id',
        'day_of_the_week',
        'start_time',
        'end_time',
        'grade_level_id',
        'program_type_id',
        'is_active',
    ];

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function programType()
    {
        return $this->belongsTo(ProgramType::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function schedules()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function enrollmentSchedules()
    {
        return $this->hasMany(EnrollmentSchedule::class);
    }

    
}
