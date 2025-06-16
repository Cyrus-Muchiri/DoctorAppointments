<?php
namespace Tests\Unit;

use App\Models\User;
use App\Models\AppointmentRequest;
use App\Policies\AppointmentRequestPolicy;
use PHPUnit\Framework\TestCase;

class AppointmentPolicyTest extends TestCase
{
    public function test_patient_can_view_own_appointment()
    {
        $patient = new User(['id' => 1, 'role' => 'patient']);
        $appointment = new AppointmentRequest(['patient_id' => 1]);

        $policy = new AppointmentRequestPolicy();

        $this->assertTrue($policy->view($patient, $appointment));
    }

    public function test_doctor_can_update_own_appointment()
    {
        $doctor = new User(['id' => 2, 'role' => 'doctor']);
        $appointment = new AppointmentRequest(['doctor_id' => 2]);

        $policy = new AppointmentRequestPolicy();

        $this->assertTrue($policy->update($doctor, $appointment));
    }

    public function test_user_cannot_view_others_appointment()
    {
        $user = new User(['id' => 3, 'role' => 'patient']);
        
        $appointment = new AppointmentRequest(['patient_id' => 1]);

        $policy = new AppointmentRequestPolicy();

        $this->assertFalse($policy->view($user, $appointment));
    }
}
