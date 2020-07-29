<?php


namespace App\Repositories\Registraion;


use App\Models\Registration;
use App\Repositories\AppRepository;
use Illuminate\Support\Facades\DB;

class RegistrationRepository extends AppRepository
{
    protected $registration;
    private $columns_select;

    public function __construct(Registration $registration)
    {
        parent::__construct($registration);
        $this->registration = $registration;

        $this->columns_select = [
            'registrations.id',
            'students.id as student_id',
            'students.name as student_name',
            'courses.id as course_id',
            'courses.title as course_title',
            'registrations.created_at',
            'registrations.updated_at'
        ];
    }

    public function all($search)
    {
        return DB::table('registrations')
            ->join('students', 'registrations.student_id', '=', 'students.id')
            ->join('courses', 'registrations.course_id', '=', 'courses.id')
            ->when($search['query'], function($q) use($search) {
                return $q->where('students.name', 'LIKE', "%{$search['query']}%")
                        ->orWhere('courses.title', 'LIKE', "%{$search['query']}%");
            })
            ->orderBy('id', 'desc')
            ->select($this->columns_select)
            ->paginate($search['limit']);
    }

    public function show($id, $relationship = "")
    {
        return DB::table('registrations')
            ->join('students', 'registrations.student_id', '=', 'students.id')
            ->join('courses', 'registrations.course_id', '=', 'courses.id')
            ->where('registrations.id','=', $id)
            ->select($this->columns_select)
            ->get()
            ->first();
    }
}
