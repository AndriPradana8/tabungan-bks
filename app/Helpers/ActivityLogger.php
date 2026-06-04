<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    public static function log(string $aktivitas, ?int $userId = null): void
    {
        ActivityLog::create([
            'user_id'    => $userId ?? Auth::id(),
            'aktivitas'  => $aktivitas,
            'ip_address' => Request::ip(),
        ]);
    }
}
