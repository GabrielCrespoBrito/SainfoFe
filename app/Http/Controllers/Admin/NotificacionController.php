<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Notifications\DatabaseNotification;

class NotificacionController extends Controller
{
  public function index(Request $request)
  {
    $type = $request->input('type','unread');
    $searchUnRead = $type == 'unread';
    $notificaciones_data = User::getAdmin()->dataNotifications($searchUnRead, false);
    
    return view('admin.notificaciones.index', [
      'type' => $type,
      'unread' => $searchUnRead,
      'notificaciones_data' => $notificaciones_data
    ]);
  }

  public function show($id)
  {
    $notificacion = DatabaseNotification::find($id);
    $title = $notificacion->read() ? 'Leida' : 'Pendiente';
    return view('admin.notificaciones.show', [
      'notificacion' => $notificacion,
      'title' => 'Notificación ' . $title,
    ]);
  }


  public function makeRead($id)
  {
    DatabaseNotification::find($id)->markAsRead();
    noti()->success('Acció exitosa');
    return back();
  }

  public function makeUnRead($id)
  {
    DatabaseNotification::find($id)->markAsUnRead();
    noti()->success('Acció exitosa');
    return back();    
  }


  
  public function readMassive(Request $request)
  {
    User::getAdmin()->notifications->whereIn('id', $request->ids)->markAsRead();
    return response()->json(['success' => true]);
  }

  public function unreadMassive(Request $request)
  {
    User::getAdmin()->notifications->whereIn('id', $request->ids)->markAsUnRead();
    return response()->json(['success' => true]);
  }
  
  public function deleteMassive(Request $request)
  {
    $notificaciones = User::getAdmin()->notifications->whereIn('id', $request->ids);
    foreach( $notificaciones as $notificacion ) {
      $notificacion->delete();
    }
    return response()->json(['success' => true]);
  }

  public function destroy($id)
  {
    DatabaseNotification::find($id)->delete();
    noti()->success('Acció exitosa');
    return redirect()->route('admin.notificaciones.index');
  }
}