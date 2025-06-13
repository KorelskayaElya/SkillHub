<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Course;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_course()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin, 'sanctum');

        $response = $this->postJson('/api/courses', [
            'title' => 'Новый курс',
            'image' => \Illuminate\Http\UploadedFile::fake()->image('course.jpg'),
        ]);

        $response->assertStatus(201);
    }

    public function test_non_admin_cannot_create_course()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/courses', [
            'title' => 'Новый курс',
            'image' => \Illuminate\Http\UploadedFile::fake()->image('course.jpg'),
        ]);

        $response->assertStatus(403);
    }

    public function test_user_can_get_own_courses()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $user->courses()->attach($course);
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/mycourses');

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $course->id]);
    }
}
