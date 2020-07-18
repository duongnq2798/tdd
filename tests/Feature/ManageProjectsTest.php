<?php

namespace Tests\Feature;

use App\Project;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /** @test */
    //  khách không thể quản lý dự án
    public function guests_cannot_manage_projects()
    {
        $project = factory('App\Project')->create();

        $this->get('/projects')->assertRedirect('login');
        $this->get('/projects/create')->assertRedirect('login');
        $this->get($project->path() . '/edit')->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
        $this->post('/projects', $project->toArray())->assertRedirect('login');
    }

    /** @test */
    // Một người dùng có thể tạo một dự án
    public function a_user_can_create_a_project()
    {
        $this->signIn();

        $this->get('/projects/create')->assertStatus(200);
        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'notes' => 'General notes here.',
        ];
        $response = $this->post('/projects', $attributes);
        $project = Project::where($attributes)->first();

        $response->assertRedirect($project->path());

        $this->get($project->path())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
    }

    /** @test */
    // Người dùng có thể cập nhật project
    public function a_user_can_update_a_project()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->patch($project->path(), $attributes = ['title' => 'Changed', 'description' => 'Changed', 'notes' => 'Changed'])
            ->assertRedirect($project->path());
        $this->get($project->path() . '/edit')->assertOk();

        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test */
    function a_user_can_update_a_projects_general_notes()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->patch($project->path(), $attributes = ['notes' => 'Changed']);

        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test */
    // Một người dùng có thể xem dự án của họ
    /** @test */
    public function a_user_can_view_their_project()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->get($project->path())
            ->assertSee($project->title);
        //    ->assertSee($project->description)
    }

    /** @test */
    // Một người dùng xác thực không thể xem các dự án của người khác
    public function an_authenticated_user_cannnot_view_the_projects_of_others()
    {
        // $this->be(factory('App\User')->create());
        // $this->withoutExceptionHandling();
        $this->signIn();
        $project = factory('App\Project')->create();
        $this->get($project->path())->assertStatus(403);
    }

    /** @test */
    // Một người dùng xác thực không thể cập nhật các dự án của người khác
    public function an_authenticated_user_cannnot_update_the_projects_of_others()
    {
        // $this->be(factory('App\User')->create());
        // $this->withoutExceptionHandling();
        $this->signIn();
        $project = factory('App\Project')->create();
        $this->patch($project->path())->assertStatus(403);
    }

    /** @test */
    // Một dự án phải có title
    public function a_project_requires_a_title()
    {
        // $this->actingAs(factory('App\User')->create());
        $this->signIn();
        $attributes = factory('App\Project')->raw(['title' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    // Một dự án phải có description
    public function a_project_requires_a_description()
    {
        // $this->actingAs(factory('App\User')->create());
        $this->signIn();
        $attributes = factory('App\Project')->raw(['description' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }
}