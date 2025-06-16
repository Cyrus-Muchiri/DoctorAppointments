<?php
namespace Tests\Feature;

use App\Models\User;
use App\Models\AppointmentRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use App\Notifications\AppointmentStatusNotification;

class AppointmentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_can_create_and_doctor_can_update_appointment()
    {
        Notification::fake();

        // Create users
        $patient = User::factory()->create(['role' => 'patient']);
        $doctor = User::factory()->create(['role' => 'doctor']);

        // Patient submits appointment
        $this->actingAs($patient, 'sanctum')
             ->postJson('/api/appointments', [
                 'doctor_id' => $doctor->id,
                 'preferred_date' => now()->addDays(2)->toDateString(),
                 'reason' => 'Need consultation',
             ])
             ->assertCreated();

        $this->assertDatabaseCount('appointment_requests', 1);
        $appointment = AppointmentRequest::first();

        // Doctor approves it
        $this->actingAs($doctor, 'sanctum')
             ->putJson("/api/appointments/{$appointment->id}", [
                 'status' => 'approved',
                 'remarks' => 'You are good to go',
             ])
             ->assertOk();

        $this->assertDatabaseHas('appointment_requests', [
            'id' => $appointment->id,
            'status' => 'approved',
            'remarks' => 'You are good to go',
        ]);

        Notification::assertSentTo($patient, AppointmentStatusNotification::class);
    }
}
