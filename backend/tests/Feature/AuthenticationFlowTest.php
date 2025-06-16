<?php

namespace Tests\Feature;

use App\Models\User;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthenticationFlowTest extends TestCase

   
{
    use RefreshDatabase; // Resets the database for each test method

    /**
     * Test that a user can successfully register.
     */
    public function test_user_can_register(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password', 
            'role' => 'patient', 
        ]);

        $response->assertCreated() 
                 ->assertJson([
                     'message' => 'User registered successfully',
                 ]);

        // Assert that the user was actually created in the database
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
            'role' => 'patient',
        ]);
    }

    /**
     * Test that a user cannot register with invalid data (e.g., duplicate email).
     */
    public function test_user_cannot_register_with_invalid_data(): void
    {
        // Create a user first to cause a conflict
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->postJson('/api/register', [
            'name' => 'Another User',
            'email' => 'existing@example.com', // Duplicate email
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
            'role' => 'patient',
        ]);

        $response->assertStatus(422) // Assert HTTP 422 Unprocessable Entity for validation errors
                 ->assertJsonValidationErrors(['email']); // Check that the email field caused an error
    }

    /**
     * Test that a user can successfully log in with valid credentials.
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => Hash::make('secretpassword'), 
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'login@example.com',
            'password' => 'secretpassword',
        ]);

        $response->assertOk()
                 ->assertJsonStructure([
                     'token', 
                 ]);
    }

    /**
     * Test that a user cannot log in with invalid credentials.
     */
    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'wronglogin@example.com',
            'password' => Hash::make('correctpassword'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'wronglogin@example.com',
            'password' => 'incorrectpassword', // Intentionally wrong password
        ]);

        $response->assertUnauthorized() // Assert HTTP 401 Unauthorized
                 ->assertJson([
                     'message' => 'Unauthorized',
                 ]);
    }

    /**
     * Test that an authenticated user can log out.
     */
    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        // Authenticate the user using Sanctum's actingAs method
        Sanctum::actingAs($user, ['api']);

        // Assert that the user initially has tokens
        // $this->assertCount(1, $user->tokens);

        $response = $this->postJson('/api/logout');

        $response->assertOk() // Assert HTTP 200 OK
                 ->assertJson([
                     'message' => 'Logged out successfully',
                 ]);

        // Refresh the user model instance from the database
        $user->refresh();

        // Assert that the user's tokens have been deleted
        $this->assertCount(0, $user->tokens);

    }

    /**
     * Test that an unauthenticated user cannot log out.
     */
    public function test_unauthenticated_user_cannot_logout(): void
    {
        // No Sanctum::actingAs() call here, simulating an unauthenticated request
        $response = $this->postJson('/api/logout');

        $response->assertUnauthorized(); // Assert HTTP 401 Unauthorized
    }
}
