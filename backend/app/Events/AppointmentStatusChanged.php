<?php
namespace App\Events;

use App\Models\AppointmentRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\PrivateChannel;

class AppointmentStatusChanged implements ShouldBroadcast
{
    public $appointment;

    public function __construct(AppointmentRequest $appointment)
    {
        $this->appointment = $appointment;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('appointments.' . $this->appointment->patient_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->appointment->id,
            'status' => $this->appointment->status,
            'remarks' => $this->appointment->remarks,
        ];
    }
}
