<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CourseService;
use App\Repositories\Course\CourseRepository;
use Symfony\Component\HttpFoundation\Response;

class CourseController extends Controller
{
    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function index(Request $request)
    {
        $search = $request->only(['query', 'limit', 'page']);

        try {
            $courses = $this->courseService->all($search);
        } catch(\Exception $e) {
            return response()->json(['message_error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($courses, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $data = $request->only(['title', 'description']);

        try {
            $createdCourse = $this->courseService->store($data);
        } catch(\Exception $e) {
            return response()->json(['message_error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($createdCourse, Response::HTTP_CREATED);
    }

    public function show($id)
    {
        try {
            $course = $this->courseService->show($id);
        } catch (\Exception $e) {
            return response()->json(['message_error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($course, Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $data = $request->only(['title', 'description']);

        try {
            $course = $this->courseService->update($data, $id);
        } catch (\Exception $e) {
            return response()->json(['message_error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($course, Response::HTTP_OK);
    }

    public function destroy($id)
    {
        try {
            $this->courseService->destroy($id);
        } catch (\Exception $e) {
            return response()->json(['message_error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
