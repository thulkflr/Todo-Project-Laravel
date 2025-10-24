<?php
namespace App\Enums;

use Illuminate\Validation\Rules\Enum;

Enum  TodoStatus: string
{
    case PENDING_TASK = 'pending';
    case COMPLETED_TASK = 'completed';

    function label(): TodoStatus
    {
    return match ($this) {
        self::PENDING_TASK => self::PENDING_TASK,
        self::COMPLETED_TASK => self::COMPLETED_TASK,
    };

    }

    function colored(): string
    {
        return match ($this) {
            self::PENDING_TASK =>'bg-yellow-100 text-yellow-800',
            self::COMPLETED_TASK => 'bg-green-100 text-green-800',
        };

    }
}
