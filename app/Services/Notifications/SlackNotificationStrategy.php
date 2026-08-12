<?php

namespace App\Services\Notifications;

use App\Contracts\NotificationInterface;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;

class SlackNotificationStrategy implements NotificationInterface
{
    public function send(Ticket $ticket, string $message): void 
    {
        Log::info("Sending SLACK ping for Ticket ID: {$ticket->id}: {$message}");
    }
}