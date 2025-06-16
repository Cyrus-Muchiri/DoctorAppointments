<?php
namespace Tests\Feature;

use App\Models\User;
use App\Models\AppointmentRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Notifications\AppointmentStatusNotification;

class AppointmentFlowTest extends TestCase
{
    use RefreshDatabase;

public function test_patient_can_create_and_doctor_can_update_appointment(): void
    {
        Notification::fake(); // Fake notifications to prevent actual sending during tests

        // Create users
        $patient = User::factory()->create(['role' => 'patient']);
        $doctor = User::factory()->create(['role' => 'doctor']);

        // Debug: Log user IDs to ensure they are created
        $this->artisan('env'); // Display environment info, helpful for debugging
        $this->assertNotNull($patient->id, 'Patient ID should not be null');
        $this->assertNotNull($doctor->id, 'Doctor ID should not be null');

        // Patient submits appointment request
        // Use Sanctum::actingAs() for clearer intent with Sanctum tests,
        // though $this->actingAs($user, 'sanctum') also works if 'sanctum' guard is default API guard.
        Sanctum::actingAs($patient, ['api']); // Pass the ability/guard explicitly if needed, 'api' is common

        $response = $this->postJson('/api/appointments', [
            'doctor_id' => $doctor->id,
            'preferred_date' => now()->addDays(2)->toDateString(),
            'reason' => 'Need consultation',
        ]);

        $response->assertCreated(); // Assert HTTP 201 Created status
        // Debug: Dump response content for failed assertions
        // $response->dump();

        $this->assertDatabaseCount('appointment_requests', 1);
        $appointment = AppointmentRequest::first();

        $this->assertDatabaseHas('appointment_requests', [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'status' => 'pending', // Default status after creation
            'reason' => 'Need consultation',
        ]);

        // Doctor approves the appointment
        Sanctum::actingAs($doctor, ['api']); // Authenticate as the doctor

        $response = $this->putJson("/api/appointments/{$appointment->id}", [
            'status' => 'approved',
            'remarks' => 'You are good to go',
        ]);

        $response->assertOk(); // Assert HTTP 200 OK status
        // Debug: Dump response content for failed assertions
        // $response->dump();

        $this->assertDatabaseHas('appointment_requests', [
            'id' => $appointment->id,
            'status' => 'approved',
            'remarks' => 'You are good to go',
        ]);

        // Assert that the patient received a notification
        Notification::assertSentTo($patient, AppointmentStatusNotification::class, function ($notification) use ($appointment) {
            return $notification->appointment->id === $appointment->id && $notification->appointment->status === 'approved';
        });
    }
}
