<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::orderBy('created_at', 'desc')->get();
        return response()->json($courses);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $course = Course::create($request->all());

        return response()->json($course, 201);
    }

    public function show($course, Request $request)
    {
        $course = Course::find($course);
        if( $course )
        {
            if( $request->included === 'category' )
            {
                $course = $course->with('category')->find($course->id);
            }
        } else {
            return response()->json(['message' => 'Course not found'], 404);
        }

        return response()->json($course);
    }

    public function update(Request $request, Course $course)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $course->update($request->all());

        return response()->json($course, 200);
    }

    public function destroy(Course $course)
    {
        $course_deleted = $course->title;
        $course->delete();
        return response()->json([
            'message' => "Course \"$course_deleted\" deleted successfully",
        ], 200);
    }
}
