<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'relation',
        'contact_number',
        'email',
        'address',

    ];



    public function studentGuardians()
    {
        return $this->hasMany(StudentGuardian::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_guardians');
    }
}
