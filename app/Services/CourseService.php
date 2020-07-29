<?php


namespace App\Services;


use App\Models\Registration;
use App\Repositories\Course\CourseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class CourseService
{
    protected $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function all($search)
    {
        return $this->courseRepository->all($search);
    }

    public function store($data)
    {
        $validator = Validator::make($data, [
            'title' => 'required|string',
            'description' => 'string'
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        return $this->courseRepository->store($data);
    }

    public function show($id)
    {
        return $this->courseRepository->show($id, 'students');
    }

    public function update($data, $id)
    {
        $validator = Validator::make($data, [
            'title' => 'required|string',
            'description' => 'string'
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        DB::beginTransaction();

        try {
            $course = $this->courseRepository->update($data, $id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update course data');
        }

        DB::commit();

        return $course;
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $registrations = Registration::where('course_id', $id)->get();

            if ($registrations) {
                foreach ($registrations as $registration) {
                    $registration->delete();
                }
            }
            $this->courseRepository->delete($id);
        } catch(\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to delete course');
        }

        DB::commit();
    }
}
