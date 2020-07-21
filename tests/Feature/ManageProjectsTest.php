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

        $this->followingRedirects()
            ->post('/projects', $attributes = factory(Project::class)->raw())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
    }


    /** @test */
    // Nhiệm vụ có thể được bao gồm như là một phần của việc tạo dự án mới
    function tasks_can_be_included_as_part_a_new_project_creation()
    {
        $this->signIn();

        $attributes = factory(Project::class)->raw();

        $attributes['tasks'] = [
            ['body' => 'Task 1'],
            ['body' => 'Task 2']
        ];

        $this->post('/projects', $attributes);

        $this->assertCount(2, Project::first()->tasks);
    }

    /** @test */
    // Người dùng có thể thấy tất cả các dự án họ đã tham gia trên bảng điều khiển của họ
    function a_user_can_see_all_projects_they_have_been_to_on_their_dashboard()
    {
        // Given we're signed in
        // and we've been invited to a project that was not by created by us
        // When I visit my dashboard
        // I should see that project
        $user = $this->signIn();
        $project = tap(ProjectFactory::create())->invite($this->signIn());
        $this->get('/projects')
            ->assertSee($project->title);
    }

    /** @test */
    // Người dùng trái phép không thể xóa một dự án
    function unauthorized_users_cannot_delete_a_projects()
    {
        $project = ProjectFactory::create();

        $this->delete($project->path())
            ->assertRedirect('/login');

        $user = $this->signIn();
        $this->delete($project->path())->assertStatus(403);

        $project->invite($user);
        $this->actingAs($user)->delete($project->path())->assertStatus(403);
    }

    /** @test */
    // Guests không thể xoá một project
    function guests_cannot_delete_a_projects()
    {
        $project = ProjectFactory::create();

        $this->delete($project->path())
            ->assertRedirect('/login');

        $this->signIn();
        $this->delete($project->path())->assertStatus(403);
    }

    /** @test */
    // Một người dùng có thể xoá một project
    function a_user_can_delete_a_project()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->delete($project->path())
            ->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects', $project->only('id'));
    }

    /** @test */
    // Người dùng có thể cập nhật project
    public function a_user_can_update_a_project()
    {
        $this->withoutExceptionHandling();
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->patch($project->path(), $attributes = ['title' => 'Changed', 'description' => 'Changed', 'notes' => 'Changed'])
            ->assertRedirect($project->path());
        $this->get($project->path() . '/edit')->assertOk();

        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test */
    // Một người dùng có thể cập nhật một ghi chú chung của dự án
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
        $this->withoutExceptionHandling();
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->get($project->path())
            ->assertSee($project->title);
        // ->assertSee($project->description);
    }

    /** @test */
    // Một người dùng trái phép không thể xem các dự án của người khác
    public function an_authenticated_user_cannnot_view_the_projects_of_others()
    {
        $this->signIn();
        $project = factory('App\Project')->create();
        $this->get($project->path())->assertStatus(403);
    }

    /** @test */
    // Một người dùng trái phép không thể cập nhật các dự án của người khác
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