<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\AppointmentRequest;
use Illuminate\Http\Request;
use App\Events\AppointmentStatusChanged;
use App\Notifications\AppointmentStatusNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AppointmentController extends Controller
{
    use AuthorizesRequests;
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
        $this->authorize('create', AppointmentRequest::class);


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
        $data= $request->validated();
        $data['doctor_id'] = $request->user()->id;
        $appointment->update($data);
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
