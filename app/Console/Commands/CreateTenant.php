<?php

namespace App\Console\Commands;

use App\Empresa;
use Illuminate\Console\Command;
use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Environment;
use Hyn\Tenancy\Models\Hostname;
use Hyn\Tenancy\Models\Website;

class CreateTenant extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	// protected $signature = 'tenant:create {subdomain} {name} {email}';
	protected $signature = 'tenant:create {empcodi}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Creates a Tenant with a subdomain, name and email. Example: php artisan tenant:create test "Test User" test@example.com';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$empcodi = $this->argument('empcodi');
		$empresa = Empresa::find($empcodi);
		$this->setEmpresa($empresa);
		
		$url_base = config('app.url_base');
		$subdomain =  $empresa->ruc();
		$name = time();
		$fqdn = "{$subdomain}.{$url_base}";
		
		// first check to make sure the tenant doesn't already exist
		if ( $this->tenantExists($fqdn)) {
			$this->error("A tenant with the subdomain '{$subdomain}' already exists.");
			return;
		}

		// if the tenant doesn't exist, we'll use the Tenancy package commands to create one
		$hostname = $this->createTenant($fqdn);
		
		// swap the environment over to the hostname
		app(Environment::class)->hostname($hostname);

		// return a success message to the console
		$this->info("Tenant $fqdn was created for {$empresa->nombre()}");
		// $this->info("The user '{$email}' can log in with password {$password}");
	}

	private function tenantExists($fqdn)
	{
		// check to see if any Hostnames in the database have the same fqdn
		return Hostname::where('fqdn', $fqdn)->exists();
	}

	private function createTenant($fqdn)
	{
		$website = new Website;
		$website->uuid = $this->getEmpresa()->getUUid();
		app(WebsiteRepository::class)->create($website);
		$hostname = new Hostname;
		$hostname->fqdn = $fqdn;
		app(HostnameRepository::class)->attach($hostname, $website);
		return $hostname;
	}

	public function setProvileges($website)
	{
	}

	/**
	 * Get the value of empresa
	 */ 
	public function getEmpresa()
	{
			return $this->empresa;
	}

	/**
	 * Set the value of empresa
	 *
	 * @return  self
	 */ 
	public function setEmpresa($empresa)
	{
			$this->empresa = $empresa;

			return $this;
	}
}