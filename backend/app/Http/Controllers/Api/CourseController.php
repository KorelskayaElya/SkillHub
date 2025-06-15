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
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if (!$request->hasFile('image') || !$request->file('image')->isValid()) {
            return response()->json(['message' => 'Неверный файл изображения'], 422);
        }

        $imagePath = $request->file('image')->store('courses', 'public');

        $course = Course::create([
            'title' => $fields['title'],
            'description' => $fields['description'] ?? '',
            'price' => $fields['price'] ?? 0,
            'image' => '/storage/' . $imagePath,
        ]);

        $course->image_url = asset($course->image);

        return response()->json(['course' => $course], 201);
    }

    public function update(Request $request, Course $course)
    {
        $user = $request->user();

        if (!$user->is_admin) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        \Log::info('UPDATE COURSE: Input fields', [
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'price' => $request->get('price'),
            'has_image' => $request->hasFile('image'),
        ]);

        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->has('title')) {
            $course->title = $request->input('title');
        }

        if ($request->has('description')) {
            $course->description = $request->input('description');
        }

        if ($request->has('price')) {
            $course->price = $request->input('price');
        }

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imagePath = $request->file('image')->store('courses', 'public');
            $course->image = '/storage/' . $imagePath;
        }

        $course->save();
        $course->image_url = asset($course->image);

        \Log::info('UPDATE COURSE: Course after save', $course->toArray());

        return response()->json(['course' => $course], 200);
    }

    public function myCourses(Request $request)
    {
        $user = $request->user();
        
        $courses = $user->courses()->get();

        return response()->json($courses);
    }

    public function show(Course $course)
    {
        return response()->json([
            'id' => $course->id,
            'title' => $course->title,
            'description' => $course->description,
            'price' => $course->price,
            'image_url' => asset($course->image),
        ]);
    }


    public function index()
    {
        $courses = Course::all();

        return response()->json($courses->map(function ($course) {
            return [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'price' => $course->price,
                'image_url' => asset($course->image),
            ];
        }));
    }

    public function buy(Request $request, $courseId)
    {
        $user = $request->user();

        $course = Course::findOrFail($courseId);

        if ($user->courses()->where('course_id', $courseId)->exists()) {
            return response()->json(['message' => 'Курс уже приобретён'], 409);
        }

        $user->courses()->attach($courseId);

        return response()->json(['message' => 'Курс успешно приобретён']);
    }


    

}
