<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user->is_admin) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $fields = $request->validate([
            'title' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if (!$request->hasFile('image') || !$request->file('image')->isValid()) {
            return response()->json(['message' => 'Неверный файл изображения'], 422);
        }

        $imagePath = $request->file('image')->store('courses', 'public');

        $course = Course::create([
            'title' => $fields['title'],
            'image' => '/storage/' . $imagePath,
        ]);

        $course->image_url = asset($course->image);

        return response()->json(['course' => $course], 201);
    }

    public function myCourses(Request $request)
    {
        $user = $request->user();
        
        $courses = $user->courses()->get();

        return response()->json($courses);
    }

    public function index()
    {
        return response()->json(Course::all());
    }
}
