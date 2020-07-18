<?php

namespace Tests\Feature;

use App\Project;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Tests\Setup\ProjectFactory as SetupProjectFactory;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    // Khách sẻ không thể thêm tasks vào project
    public function guests_can_not_add_tasks_to_projects()
    {
        $project = factory('App\Project')->create();

        $this->post($project->path() . '/tasks')->assertRedirect('login');
    }

    /** @test */
    // Chỉ chủ sổ hữu mới có thể thêm Task vào Project
    function only_the_owner_of_a_project_may_add_tasks()
    {
        $this->signIn();

        $project = factory('App\Project')->create();

        $this->post($project->path() . '/tasks', ['body' => 'Test task'])
            ->assertStatus(403);
        $this->assertDatabaseMissing('tasks', ['body' => 'Test task']);
    }

    /** @test */
    //  chỉ chủ sở hữu của một dự án có thể cập nhật một nhiệm vụ
    function only_the_owner_of_a_project_may_update_a_tasks()
    {
        $this->signIn();

        $project = ProjectFactory::withTasks(1)->create();

        $this->patch($project->tasks[0]->path(), ['body' => 'changed'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'changed']);
    }

    /** @test */
    // Một dự án có thể có nhiệm vụ
    public function a_project_can_have_tasks()
    {
        // $this->signIn();

        // $project = auth()->user()->projects()->create(
        //     factory(Project::class)->raw()
        // );
        $project = ProjectFactory::create();


        $this->actingAs($project->owner)
            ->post($project->path() . '/tasks', ['body' => 'Test task']);

        $this->get($project->path())
            ->assertSee('Test task');
    }
    /** @test */
    //  một nhiệm vụ có thể được cập nhật
    function a_task_can_be_updated()
    {

        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), [
                'body' => 'changed'
            ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'changed'
        ]);
    }

    /** @test */
    // Một nhiệm vụ có thể được completed
    function a_task_can_be_completed()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), [
                'body' => 'changed',
                'completed' => true
            ]);
        $this->assertDatabaseHas('tasks', [
            'body' => 'changed',
            'completed' => true
        ]);
    }

    /** @test */
    // Một nhiệm vụ có thể không được completed
    /** @test */
    function a_task_can_be_marked_as_incomplete()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), [
                'body' => 'changed',
                'completed' => true
            ]);

        $this->patch($project->tasks[0]->path(), [
            'body' => 'changed',
            'completed' => false
        ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'changed',
            'completed' => false
        ]);
    }

    /** @test */
    // Mỗi Task sẽ yêu cầu Body
    public function a_task_requires_a_body()
    {
        $project = ProjectFactory::create();

        $attributes = factory('App\Project')->raw(['body' => '']);

        $this->actingAs($project->owner)
            ->post($project->path() . '/tasks', $attributes)
            ->assertSessionHasErrors('body');
    }
}