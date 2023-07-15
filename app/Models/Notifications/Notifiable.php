<?php

namespace App\Models\Notifications;

use Illuminate\Notifications\RoutesNotifications;
use App\Models\Notifications\HasDatabaseNotifications;

trait Notifiable
{
  use HasDatabaseNotifications, RoutesNotifications;
}
