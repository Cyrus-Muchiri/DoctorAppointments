<?php

namespace App\Policies;

use App\Models\AppointmentRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AppointmentRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AppointmentRequest $appointmentRequest): bool
    {
       
        return $user->id === $appointmentRequest->patient_id
            || $user->id === $appointmentRequest->doctor_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'patient';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AppointmentRequest $appointmentRequest): bool
    {
        return $user->role === 'doctor' ;
    }

   public function delete(User $user, AppointmentRequest $appointmentRequest): bool
    {
        return $user->role === 'patient' && $user->id === $appointmentRequest->patient_id && $appointmentRequest->status === 'pending';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AppointmentRequest $appointmentRequest): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AppointmentRequest $appointmentRequest): bool
    {
        return false;
    }
}
