<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'name',
        'email',
        'birth_date'
    ];

    protected $hidden = ['pivot'];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'registrations', 'student_id', 'course_id')
            ->withTimestamps();
    }
}
