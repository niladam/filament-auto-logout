<?php

namespace Niladam\FilamentAutoLogout\Enums;

enum AutoLogoutPosition: string
{
    case BOTTOM_RIGHT = 'bottom-right';
    case BOTTOM_LEFT = 'bottom-left';

    public function getPosition(): string
    {
        return match ($this) {
            self::BOTTOM_RIGHT => 'right',
            self::BOTTOM_LEFT => 'left',
        };
    }
}
