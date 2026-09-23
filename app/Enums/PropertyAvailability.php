<?php

namespace App\Enums;

enum PropertyAvailability: string
{
    case Available = 'Available';
    case NotAvailable = 'Not Available';

    public function label(): string
    {
        return match ($this) {
            self::Available => 'Available',
            self::NotAvailable => 'Not Available',
        };
    }
}
