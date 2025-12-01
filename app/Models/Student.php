<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'learner_reference_number',
        'first_name',
        'middle_name',
        'last_name',
        'extension_name',
        'nickname',
        'birthdate',
        'birthplace',
        'gender',
        'nationality',
        'spoken_dialect',
        'other_spoken_dialect',
        'religion',
        'address',
        'student_status',
        'user_id'
    ];

    public function studentGuardians()
    {
        return $this->hasMany(StudentGuardian::class);
    }

    public function guardians()
    {
        return $this->belongsToMany(Guardian::class, 'student_guardians');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function spedStudentDetails()
    {
        return $this->hasOne(SpedStudent::class);
    }
}
