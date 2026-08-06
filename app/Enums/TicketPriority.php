<?php

namespace App\Enums;

enum TicketPriority: string 
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';

    public function label(): string 
    {
        return match($this) 
        {
            self::LOW => 'Low',
            self::MEDIUM => 'Medium',
            self::HIGH => 'High',
        };
    }

    public function badgeColor(): string 
    {
        return match($this)
        {
            self::LOW => 'bg-gray-100 text-gray-800',
            self::MEDIUM => 'bg-yellow-100 text-yellow-800',
            self::HIGH => 'bg-red-100 text-red-800',
        };
    }
}