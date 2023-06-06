<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $user = User::factory()->create();

        $this->post(route('auth-login'), [
            "email" => $user->email,
            "password" => 'password',
        ])->assertStatus(200);
    }
}
