<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;

use App\Models\AppointmentRequest;

class AppointmentStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $appointment;

    public function __construct(AppointmentRequest $appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast']; // You can also add 'mail' if needed
    }

    public function toDatabase($notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'doctor_name' => $this->appointment->doctor->name,
            'status' => $this->appointment->status,
            'remarks' => $this->appointment->remarks,
            'preferred_date' => $this->appointment->preferred_date,
        ];
    }

        /**
     * Get the mail representation of the notification.
     */public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Your appointment status has been updated.')
                    ->action('View Appointment', url('/appointments/' . $this->appointment->id))
                    ->line('Thank you for using our application!');
    }


    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'appointment_id' => $this->appointment->id,
            'doctor_name' => $this->appointment->doctor->name,
            'status' => $this->appointment->status,
            'remarks' => $this->appointment->remarks,
            'preferred_date' => $this->appointment->preferred_date,
        ]);
    }
}

