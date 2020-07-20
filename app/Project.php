<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
use PDO;

class Project extends Model
{
    use RecordsActivity;
    /**
     * Attributes to guard against mass assignment.
     * Thuộc tính để bảo vệ chống phân công hàng loạt
     * $guarded, biến này sẽ chứa các field mà người dùng không được phép thay đổi.
     * @var array
     */
    protected $guarded = [];


    /**
     *  The path to the project.
     *
     * @return string
     */
    public function path()
    {
        return "/projects/{$this->id}";
    }
    /**
     * The owner of the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * The tasks associated with the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    /**
     * Add a task to the project.
     *
     * @param  string $body
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function addTask($body)
    {
        return $this->tasks()->create(compact('body'));
    }

    /**
     * Fix bug hai array không bằng nhau do khác Date function
     * https://laravel.com/docs/7.x/upgrade#date-serialization
     * @param DateTimeInterface $date
     * @return void
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * Invite a user to the project.
     *
     * @param \App\User $user
     */
    public function invite(User $user)
    {
        $this->members()->attach($user);
    }

    /**
     * Get all members that are assigned to the team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members')->withTimestamps();
    }
}