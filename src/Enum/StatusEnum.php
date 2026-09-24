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
            self::OPEN => 'bg-status-open text-status-open-on',
            self::IN_PROGRESS => 'bg-status-in-progress text-status-in-progress-on',
            self::DONE => 'bg-status-done text-status-done-on',
            self::CLOSE => 'bg-status-close text-status-close-on',
        };
    }
}
