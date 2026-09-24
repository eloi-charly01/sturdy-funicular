<?php

namespace App\Enum;

enum ProjectStatusEnum: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case DONE = 'done';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'En attente',
            self::IN_PROGRESS => 'En cours',
            self::DONE => 'Terminé',
            self::CANCELLED => 'Annulé',
        };
    }

    public function colorClass(): string
    {
        return match ($this) {
            self::PENDING => 'bg-status-pending text-status-pending-on',
            self::IN_PROGRESS => 'bg-status-in-progress text-status-in-progress-on',
            self::DONE => 'bg-status-done text-status-done-on',
            self::CANCELLED => 'bg-status-cancelled text-status-cancelled-on',
        };
    }
}
