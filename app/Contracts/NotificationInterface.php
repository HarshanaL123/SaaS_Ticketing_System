<?php

namespace App\Contracts;

use App\Models\Ticket;

interface NotificationInterface
{
    public function send(Ticket $ticket, string $message): void;
}