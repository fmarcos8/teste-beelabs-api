<?php


namespace App\Services;

use App\Models\Registration;
use App\Repositories\Student\StudentRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class StudentService
{
    protected $studentRepository;

    public function __construct(StudentRepository $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function all($search)
    {
        return $this->studentRepository->all($search);
    }

    public function store($data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:students',
            'birth_date' => 'date'
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        return $this->studentRepository->store($data);
    }

    public function show($id)
    {
        return $this->studentRepository->show($id, 'courses');
    }

    public function update($data, $id)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'birth_date' => 'date'
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        DB::beginTransaction();

        try {
            $student = $this->studentRepository->update($data, $id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update student data');
        }

        DB::commit();

        return $student;
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {

            $registrations = Registration::where('student_id', $id)->get();

            if ($registrations) {
                foreach ($registrations as $registration) {
                    $registration->delete();
                }
            }
            $this->studentRepository->delete($id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to delete student');
        }

        DB::commit();
    }
}
