<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        DB::statement('PRAGMA foreign_keys=on;');

        // $this->withExceptionHandling();
    }

    /**
     * Log in as a newly created user or if specified as the requested user.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @return $this
     */
    protected function signIn($user = null)
    {
        $user = $user ?? $this->create('App\User');

        $this->actingAs($user);

        return $this;
    }

    /**
     * Logs out the current user.
     */
    protected function logout()
    {
        Auth::logout();
    }

    /**
     * Create a class from a given factory.
     *
     * @param $class
     * @param array $attributes
     * @param null $times
     * @return mixed
     */
    protected function create($class, $attributes = [], $times = null)
    {
        return factory($class, $times)->create($attributes);
    }

    /**
     * Make a class from a given factory.
     *
     * @param $class
     * @param array $attributes
     * @param null $times
     * @return mixed
     */
    protected function make($class, $attributes = [], $times = null)
    {
        return factory($class, $times)->make($attributes);
    }
}
