<?php

namespace App\Enums;

enum TicketStatus: string 
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case CLOSED = 'closed';

    public function label(): string 
    {
        return match($this)
        {
            self::OPEN => 'Open',
            self::IN_PROGRESS => 'In Progress',
            self::CLOSED => 'Closed',
        };
    }

    public function badgeColor(): string 
    {
        return match($this)
        {
            self::OPEN => 'bg-green-100 text-green-800',
            self::IN_PROGRESS => 'bg-blue-100 text-blue-800',
            self::CLOSED => 'bg-gray-100 text-gray-800',
        };
    }
}
