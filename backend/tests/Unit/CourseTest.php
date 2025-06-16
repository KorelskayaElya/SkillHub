<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourseTest extends TestCase {
    use RefreshDatabase;

    public function test_course_can_be_created()
    {
        $course = Course::create([
            'title' => 'Тест курс',
            'description' => 'Описание',
            'price' => 1000,
            'image' => '/storage/test.jpg',
        ]);

        $this->assertEquals('Тест курс', $course->title);
        $this->assertEquals(1000, $course->price);
    }

    public function test_user_course_relationship()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();

        $user->courses()->attach($course);

        $this->assertTrue($user->courses->contains($course));
    }
}