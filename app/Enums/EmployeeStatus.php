<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum EmployeeStatus: string implements HasLabel
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case ON_LEAVE = 'on_leave';

    public function getLabel(): ?string
    {
        return str(str($this->value)->replace('_', ' '))->title();
    }

    public function getColor(): string
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::INACTIVE => 'danger',
            self::ON_LEAVE => 'warning',
        };
    }
}
