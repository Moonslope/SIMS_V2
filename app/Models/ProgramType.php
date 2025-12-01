<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramType extends Model
{
    protected $fillable = [
        'program_name',
        'description',
        'is_active',
    ];


    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function feeStructures()
    {
        return $this->hasMany(FeeStructure::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
