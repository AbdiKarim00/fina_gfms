<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;

class BookingStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;
    protected $oldStatus;
    protected $newStatus;
    protected $actionBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking, string $oldStatus, string $newStatus, $actionBy = null)
    {
        $this->booking = $booking;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->actionBy = $actionBy;
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
        $subject = "Booking {$this->newStatus}: {$this->booking->vehicle->registration_number}";
        
        $message = (new MailMessage)
            ->subject($subject)
            ->greeting("Hello {$notifiable->name},")
            ->line("Your booking status has been updated.");

        // Add status-specific content
        switch ($this->newStatus) {
            case 'approved':
                $message->line("✅ Your booking has been approved!")
                    ->line("**Vehicle:** {$this->booking->vehicle->registration_number} ({$this->booking->vehicle->make} {$this->booking->vehicle->model})")
                    ->line("**Date & Time:** {$this->booking->start_date->format('M j, Y H:i')} - {$this->booking->end_date->format('M j, Y H:i')}")
                    ->line("**Destination:** {$this->booking->destination}")
                    ->line("**Purpose:** {$this->booking->purpose}");
                
                if ($this->booking->driver) {
                    $message->line("**Driver:** {$this->booking->driver->name} ({$this->booking->driver->phone})");
                }
                
                $message->action('View Booking Details', url("/bookings/{$this->booking->id}"))
                    ->line('Please ensure you have all necessary documents and arrive on time.');
                break;

            case 'rejected':
                $message->line("❌ Your booking has been rejected.")
                    ->line("**Reason:** {$this->booking->rejection_reason}")
                    ->line("**Original Request:** {$this->booking->purpose}")
                    ->line("**Requested Date:** {$this->booking->start_date->format('M j, Y H:i')} - {$this->booking->end_date->format('M j, Y H:i')}")
                    ->action('Submit New Booking', url('/bookings/create'))
                    ->line('You may submit a new booking request with different details.');
                break;

            case 'cancelled':
                $message->line("🚫 Your booking has been cancelled.")
                    ->line("**Vehicle:** {$this->booking->vehicle->registration_number}")
                    ->line("**Date:** {$this->booking->start_date->format('M j, Y H:i')} - {$this->booking->end_date->format('M j, Y H:i')}")
                    ->line('If you still need transport, please submit a new booking request.');
                break;

            case 'completed':
                $message->line("✅ Your booking has been completed.")
                    ->line("**Vehicle:** {$this->booking->vehicle->registration_number}")
                    ->line("**Date:** {$this->booking->start_date->format('M j, Y H:i')} - {$this->booking->end_date->format('M j, Y H:i')}")
                    ->line('Thank you for using the fleet management system.');
                break;
        }

        if ($this->actionBy) {
            $message->line("Action taken by: {$this->actionBy->name}");
        }

        return $message->line('Thank you for using the Government Fleet Management System.');
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
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'start_date' => $this->booking->start_date->toISOString(),
            'end_date' => $this->booking->end_date->toISOString(),
            'destination' => $this->booking->destination,
            'purpose' => $this->booking->purpose,
            'action_by' => $this->actionBy ? $this->actionBy->name : null,
            'message' => $this->getNotificationMessage(),
        ];
    }

    /**
     * Get a short notification message.
     */
    private function getNotificationMessage(): string
    {
        $vehicle = $this->booking->vehicle->registration_number;
        $date = $this->booking->start_date->format('M j, Y');
        
        return match($this->newStatus) {
            'approved' => "Your booking for {$vehicle} on {$date} has been approved.",
            'rejected' => "Your booking for {$vehicle} on {$date} has been rejected.",
            'cancelled' => "Your booking for {$vehicle} on {$date} has been cancelled.",
            'completed' => "Your booking for {$vehicle} on {$date} has been completed.",
            default => "Your booking status has changed to {$this->newStatus}.",
        };
    }
}
