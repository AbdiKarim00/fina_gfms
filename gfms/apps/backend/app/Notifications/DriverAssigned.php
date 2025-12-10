<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;

class DriverAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("New Assignment: {$this->booking->vehicle->registration_number}")
            ->greeting("Hello {$notifiable->name},")
            ->line("You have been assigned to a new booking.")
            ->line("**Vehicle:** {$this->booking->vehicle->registration_number} ({$this->booking->vehicle->make} {$this->booking->vehicle->model})")
            ->line("**Date & Time:** {$this->booking->start_date->format('M j, Y H:i')} - {$this->booking->end_date->format('M j, Y H:i')}")
            ->line("**Destination:** {$this->booking->destination}")
            ->line("**Purpose:** {$this->booking->purpose}")
            ->line("**Passengers:** {$this->booking->passengers}")
            ->line("**Requester:** {$this->booking->requester->name} ({$this->booking->requester->phone})")
            ->action('View Assignment Details', url("/bookings/{$this->booking->id}"))
            ->line('Please ensure the vehicle is ready and arrive at the pickup location on time.')
            ->line('Contact the requester if you have any questions about the assignment.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'vehicle_registration' => $this->booking->vehicle->registration_number,
            'start_date' => $this->booking->start_date->toISOString(),
            'end_date' => $this->booking->end_date->toISOString(),
            'destination' => $this->booking->destination,
            'purpose' => $this->booking->purpose,
            'passengers' => $this->booking->passengers,
            'requester_name' => $this->booking->requester->name,
            'requester_phone' => $this->booking->requester->phone,
            'message' => "You have been assigned to drive {$this->booking->vehicle->registration_number} on {$this->booking->start_date->format('M j, Y')}.",
        ];
    }
}
