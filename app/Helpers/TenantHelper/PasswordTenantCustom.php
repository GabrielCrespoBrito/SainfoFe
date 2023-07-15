<?php
namespace App\Helpers\TenantHelper;

use Hyn\Tenancy\Contracts\Database\PasswordGenerator;


class PasswordTenantCustom implements PasswordGenerator
{

  public function generate(\Hyn\Tenancy\Contracts\Website $website): string
  {
    return (string) env('DB_PASSWORD');
  }

}