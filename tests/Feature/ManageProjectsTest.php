<?php

namespace Tests\Feature;

use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /** @test */
    //  khách không thể quản lý dự án
    public function guests_cannot_manage_projects()
    {
        $project = factory('App\Project')->create();

        $this->get('/projects')->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
        $this->post('projects', $project->toArray())->assertRedirect('login');
    }


    /** @test */
    // Một người dùng có thể tạo một dự án
    public function a_user_can_create_a_project()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        // $this->actingAs(factory('App\User')->create());
        $this->get('/projects/create')->assertStatus(200);
        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph
        ];
        $response = $this->post('/projects', $attributes);

        $project = Project::where($attributes)->first();

        $response->assertRedirect();

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('projects')->assertSee($attributes['title']);
    }

    /** @test */
    // Một người dùng có thể xem dự án của họ
    public function a_user_can_view_their_project()
    {
        // $this->be(factory('App\User')->create());
        $this->signIn();
        $this->withoutExceptionHandling();
        $project = factory('App\Project')->create(['owner_id' => auth()->id()]);

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
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