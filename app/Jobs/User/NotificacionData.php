<?php

namespace App\Jobs\User;

use App\Helpers\NotificacionDatabaseHelper;
use App\User;

class NotificacionData
{
  public $user;
  public $searchUnRead;
  public $take;

  public function __construct(User $user, $searchUnRead = true, $take = true)
  {
    $this->user = $user;
    $this->searchUnRead = $searchUnRead;
    $this->take = $take;
  }

  public function handle()
  {
    $notifications = $this->searchUnRead ? 
      $this->user->unreadNotifications :
      $this->user->readNotifications;
      

    $items = $this->take ? $notifications->take(10) : $notifications;
    $items_data = [];

    foreach( $items as $item ){
      $items_data[] = NotificacionDatabaseHelper::getFormat($item);
    }

    return (object) [
      'count' => $notifications->count(),
      'items' => $items_data,
    ];
  }
}
