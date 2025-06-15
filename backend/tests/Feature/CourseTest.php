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

    public function test_user_can_buy_course()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson("/api/courses/{$course->id}/buy");

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Курс успешно приобретён']);
        $this->assertDatabaseHas('course_user', [
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);
    }

    public function test_user_cannot_buy_same_course_twice()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $user->courses()->attach($course->id);

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson("/api/courses/{$course->id}/buy");

        $response->assertStatus(409);
        $response->assertJson(['message' => 'Курс уже приобретён']);
    }

    public function test_admin_can_update_course()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $course = Course::factory()->create([
            'title' => 'Старый заголовок',
        ]);

        $this->actingAs($admin, 'sanctum');

        $response = $this->patchJson("/api/courses/{$course->id}", [
            'title' => 'Новый заголовок',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'title' => 'Новый заголовок',
        ]);
    }

    public function test_user_can_view_all_courses()
    {
        Course::factory()->count(3)->create();

        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/courses');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    public function test_user_can_view_single_course()
    {
        $course = Course::factory()->create();

        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson("/api/courses/{$course->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $course->id,
            'title' => $course->title,
        ]);
    }

    public function test_guest_cannot_buy_course()
    {
        $course = Course::factory()->create();

        $response = $this->postJson("/api/courses/{$course->id}/buy");

        $response->assertStatus(401); // Unauthorized
    }
}
