<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
use Illuminate\Support\Arr;

class Project extends Model
{
    /**
     * Attributes to guard against mass assignment.
     * Thuộc tính để bảo vệ chống phân công hàng loạt
     * $guarded, biến này sẽ chứa các field mà người dùng không được phép thay đổi.
     * @var array
     */
    protected $guarded = [];

    public $old = [];

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
     * Các nhiệm vụ liên quan đến dự án.
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
     * Record activity for a project.
     *
     * @param string $type
     */
    public function recordActivity($description)
    {
        $this->activity()->create([
            'description' => $description,
            'changes' => $this->activityChanges($description)
        ]);
    }

    protected function activityChanges($description)
    {
        if ($description == 'updated') {
            return [
                'before' => Arr::except(array_diff($this->old, $this->getAttributes()), 'updated_at'),
                'after' => Arr::except($this->getChanges(), 'updated_at')
            ];
        }
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
     * The activity feed for the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activity()
    {
        return $this->hasMany(Activity::class)->latest();
    }
}