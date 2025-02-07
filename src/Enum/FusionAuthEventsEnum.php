<?php

declare(strict_types=1);

namespace App\Enum;

enum FusionAuthEventsEnum: string
{
    case UserCreate = 'user.create';
    case UserLoginFailed = 'user.login.failed';
}