<?php

namespace App\Enum;

enum Type: string
{
    case INFORMATION = 'Notification informative';
    case URGENT = 'Notification urgente';
    case RAPPEL = 'Notification de rappel';
}
