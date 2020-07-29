<?php


namespace App\Services;


use App\Models\Registration;
use App\Repositories\Registraion\RegistrationRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class RegistrationService
{
    protected $registrationRepository;

    public function __construct(RegistrationRepository $registrationRepository)
    {
        $this->registrationRepository = $registrationRepository;
    }

    public function all($search)
    {
        return $this->registrationRepository->all($search);
    }

    public function store($data)
    {
        $validator = Validator::make($data, [
            'student_id' => 'required',
            'course_id' => 'required'
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        $this->verifyExistsRegistration($data);

        return $this->registrationRepository->store($data);
    }

    public function show($id)
    {
        return $this->registrationRepository->show($id, '');
    }

    public function update($data, $id)
    {
        $validator = Validator::make($data, [
            'student_id' => 'required',
            'course_id' => 'required'
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        $this->verifyExistsRegistration($data);

        DB::beginTransaction();

        try {
            $registration = $this->registrationRepository->update($data, $id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update student data');
        }

        DB::commit();

        return $registration;
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $this->registrationRepository->delete($id);
        } catch(\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to delete registration');
        }

        DB::commit();
    }

    private function verifyExistsRegistration($data)
    {
        $registration = Registration::where([
                'student_id' => $data['student_id'],
                'course_id' => $data['course_id']
            ])
            ->get()
            ->first();

        if ($registration) {
            abort(500,'This student is already enrolled in this course');
        }
    }
}
