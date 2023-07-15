<?php

use Illuminate\Database\Seeder;
use App\SettingSystem;

class SettingsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    SettingSystem::getNews();
    return;
  }
}
