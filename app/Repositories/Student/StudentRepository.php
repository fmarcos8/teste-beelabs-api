<?php


namespace App\Repositories\Student;

use App\Models\Student;
use App\Repositories\AppRepository;

class StudentRepository extends AppRepository
{
    protected $model;

    public function __construct(Student $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    public function all($search)
    {
        return $this->model
            ->with(['courses' => function($q) {
                $q->select('courses.id', 'courses.title');
            }])
            ->when($search['query'], function($q) use($search) {
                return $q->where('name', 'LIKE', "%{$search['query']}%")
                    ->orWhere('email', 'LIKE', "%{$search['query']}%");
            })
            ->orderBy('id', 'desc')
            ->paginate($search['limit']);
    }
}
