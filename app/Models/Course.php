<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title',
        'description'
    ];

    protected $hidden = ['pivot'];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'registrations', 'course_id', 'student_id')
            ->withTimestamps();
    }


}
