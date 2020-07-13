<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
// use PHPUnit\Framework\TestCase; cai nay khong dung duoc bao error

class UserTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_user_has_projects()
    {
        $this->withoutMiddleware();
        $user = factory('App\User')->create();

        $this->assertInstanceOf(Collection::class, $user->projects);
    }
}