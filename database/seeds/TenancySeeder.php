<?php
// database/seeds/CrearTenantSeeder.php

use Illuminate\Database\Seeder;
use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Environment;
use Hyn\Tenancy\Models\Hostname;
use Hyn\Tenancy\Models\Website;

class TenancySeeder extends Seeder
{
	public function run()
	{
		\Log::info((array)  "Data");
	}
}
