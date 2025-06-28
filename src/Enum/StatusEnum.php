<?php

namespace App\Enum;

enum StatusEnum: string
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case DONE = 'done';
    case CLOSE = 'close';

    public function label(): string
    {
        return match ($this) {
            self::OPEN => 'Ouvert',
            self::IN_PROGRESS => 'En cours',
            self::DONE => 'Terminé',
            self::CLOSE => 'Fermé',
        };
    }

    public function colorClass(): string
    {
        return match ($this) {
            self::OPEN => 'bg-blue-100 text-blue-800',
            self::IN_PROGRESS => 'bg-yellow-100 text-yellow-800',
            self::DONE => 'bg-green-100 text-green-800',
            self::CLOSE => 'bg-gray-100 text-gray-800',
        };
    }
}
