<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    
    public function enroll(Request $request)
    {
        $user = $request->user();

        $courseId = $request->input('course_id');

        $course = Course::findOrFail($courseId);

        $user->courses()->syncWithoutDetaching([$course->id]);

        return response()->json(['message' => 'Вы успешно записаны на курс']);
    }
}
