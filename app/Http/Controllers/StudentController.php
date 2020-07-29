<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentUpdateRequest;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function index(Request $request)
    {
        $search = $request->only(['query', 'limit', 'page']);

        try {
            $students = $this->studentService->all($search);
        } catch (\Exception $e) {
            return response()->json(['message_error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($students, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $data = $request->only(['name', 'email', 'birth_date']);

        try {
            $createdStudent = $this->studentService->store($data);
        } catch (\Exception $e) {
            return response()->json(['message_error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json($createdStudent, Response::HTTP_CREATED);
    }

    public function show($id)
    {
        try {
            $student = $this->studentService->show($id);
        } catch (\Exception $e) {
            return response()->json(['message_error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($student, Response::HTTP_OK);
    }

    public function update(StudentUpdateRequest $request, $id)
    {
        $data = $request->only(['name', 'birth_date']);

        try {
            $student = $this->studentService->update($data, $id);
        } catch (\Exception $e) {
            return response()->json(['message_error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

       return response()->json($student, Response::HTTP_OK);
    }

    public function destroy($id)
    {
        try {
            $this->studentService->destroy($id);
        } catch (\Exception $e) {
            return response()->json(['message_error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
