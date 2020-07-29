<?php


namespace App\Repositories\Course;


use App\Models\Course;
use App\Repositories\AppRepository;

class CourseRepository extends AppRepository
{
    protected $course;

    public function __construct(Course $course)
    {
        parent::__construct($course);
        $this->course = $course;
    }

    public function all(array $search)
    {
         $courses = $this->course
            ->with(['students' => function($q) {
                $q->select('students.id', 'students.name', 'students.email');
            }])
            ->when($search['query'], function($query) use($search) {
                return $query->where("title", "LIKE", "%{$search['query']}%");
            })
            ->orderBy('id', 'desc');

         if ($search['limit']) {
             $courses = $courses->paginate($search['limit']);
         } else {
             $courses = $courses->get();
         }

         return $courses;
    }
}
