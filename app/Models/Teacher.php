<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'address',
        'contact_number',
        'is_active',
    ];

    public function sections()
    {
        return $this->hasMany(Section::class);
    }
}
