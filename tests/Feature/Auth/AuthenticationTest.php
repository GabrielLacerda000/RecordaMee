<?php

use App\Models\User;

use Laravel\Sanctum\Sanctum;

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertNoContent();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $token = $user->createToken('TestToken')->plainTextToken;

     $response = $this
        ->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json', 
        ])
        ->post('/logout');

    $response->assertStatus(200);

    $this->assertCount(0, $user->fresh()->tokens);
});

