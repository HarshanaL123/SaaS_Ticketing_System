<?php 

namespace App\Services\Notifications;

use App\Contracts\NotificationInterface;
use App\Models\Ticket;
use Illuminate\Support\Facades\Mail;

class EmailNotificationStrategy implements NotificationInterface
{
    public function send(Ticket $ticket, string $message): void
    {
        Log::info("Sending EMAIL to User ID {$ticket->user_id}: {$message}");
    }
} 