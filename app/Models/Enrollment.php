<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enrollment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'academic_year_id',
        'program_type_id',
        'grade_level_id',
        'section_id',
        'date_enrolled',
        'enrollment_status',
        'createdBy'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function programType()
    {
        return $this->belongsTo(ProgramType::class);
    }

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'createdBy');
    }

    public function enrollmentSubjects()
    {
        return $this->hasMany(EnrollmentSubject::class);
    }

    public function enrollmentSchedules()
    {
        return $this->hasMany(EnrollmentSchedule::class);
    }

    public function billing()
    {
        return $this->hasOne(Billing::class);
    }
}
