<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'student_id',
        'document_name',
        'document_type',
        'file_path',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
