<?php

namespace App\Http\Controllers;

use App\Services\RegistrationService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationController extends Controller
{
    protected $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    public function index(Request $request)
    {
        $search = $request->only(['query', 'limit', 'page']);
        try {
            $registrations = $this->registrationService->all($search);
        } catch(\Exception $e) {
            return response()->json(['message_error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($registrations, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $data = $request->only(['student_id', 'course_id']);

        try {
            $registration = $this->registrationService->store($data);
        } catch (\Exception $e) {
            return response()->json(['message_error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($registration, Response::HTTP_CREATED);
    }

    public function show($id)
    {
        try {
            $registration = $this->registrationService->show($id);
        } catch (\Exception $e) {
            return response()->json(['message_error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($registration, Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $data = $request->only(['course_id', 'student_id']);

        try {
            $registration = $this->registrationService->update($data, $id);
        } catch (\Exception $e) {
            return response()->json(['message_error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($registration, Response::HTTP_OK);
    }

    public function destroy($id)
    {
        try {
            $this->registrationService->destroy($id);
        } catch (\Exception $e) {
            return response()->json(['message_error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
