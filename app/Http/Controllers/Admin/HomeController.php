<?php

namespace App\Http\Controllers\Admin;

use App\Admin\SystemStat\SystemStat;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
  public function index()
  {
    $system_stats = new SystemStat();
    $system_stats->repository()->clearCache('all');    
    $stats = $system_stats->getStatsHome()->value;
    $acciones = $system_stats->findByName(SystemStat::ACCIONES_PENDIENTES);
    $hasPendientes = (bool) $acciones->value;
    
    return view('admin.home', compact('stats', 'acciones' , 'hasPendientes'));
  }
}

