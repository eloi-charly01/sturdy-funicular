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
            self::PENDING => 'bg-gray-100 text-gray-800',
            self::IN_PROGRESS => 'bg-yellow-100 text-yellow-800',
            self::DONE => 'bg-green-100 text-green-800',
            self::CANCELLED => 'bg-red-100 text-red-800',
        };
    }
}
