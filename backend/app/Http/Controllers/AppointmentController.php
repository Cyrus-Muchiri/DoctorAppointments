<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\AppointmentRequest;
use Illuminate\Http\Request;
use App\Events\AppointmentStatusChanged;
use App\Notifications\AppointmentStatusNotification;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->role === 'patient') {
            return $user->sentRequests()->latest()->paginate();
        } elseif ($user->role === 'doctor') {
            return $user->receivedRequests()->latest()->paginate();
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppointmentRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();
        if ($user->role !== 'patient') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $appointmentRequest = $user->sentRequests()->create($data);

        return response()->json($appointmentRequest, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $appointment = AppointmentRequest::findOrFail($id);
        $this->authorize('view', $appointment);
        return $appointment;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAppointmentRequest $request, AppointmentRequest $appointment)
    {
        $this->authorize('update', $appointment);
        $appointment->update($request->validated());
        // broadcast + notify patient
        broadcast(new AppointmentStatusChanged($appointment))->toOthers();
        $appointment->patient->notify(new AppointmentStatusNotification($appointment));
        return $appointment;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $appointment = AppointmentRequest::findOrFail($id);
        $this->authorize('delete', $appointment);
        $appointment->delete();
        // broadcast + notify patient
        broadcast(new AppointmentStatusChanged($appointment))->toOthers();
        $appointment->patient->notify(new AppointmentStatusNotification($appointment));
        return response()->json(['message' => 'Appointment deleted successfully'], 204);
    }
}
