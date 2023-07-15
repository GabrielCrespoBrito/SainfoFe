<?php


use Hyn\Tenancy\Contracts\Repositories\CustomerRepository;
use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Models\Customer;
use Hyn\Tenancy\Models\Hostname;
use Hyn\Tenancy\Models\Website;
use Illuminate\Database\Seeder;

class BuildDatabasesForTenants extends Seeder
{
/**
* Run the database seeds.
*
* @return void
*/
public function run()
{

  dd("tenancy");

$customers = [
['database' => 'customer_foo_tenancy', 'domain' => 'foo.multi-tenancy.job', 'name' => 'Foo Customer', 'email' => 'customer@foo.com'],
['database' => 'customer_bar_tenancy', 'domain' => 'bar.multi-tenancy.job', 'name' => 'Bar Customer', 'email' => 'customer@bar.com'],
['database' => 'customer_baz_tenancy', 'domain' => 'baz.multi-tenancy.job', 'name' => 'Baz Customer', 'email' => 'customer@baz.com'],
];

foreach ($customers as $customer) {
/*
|--------------------------------------------------------------------------
| CREATE THE WEBSITE
|--------------------------------------------------------------------------
*/
$website = new Website(['uuid' => $customer['database']]);
app(WebsiteRepository::class)->create($website);

/*
|--------------------------------------------------------------------------
| CREATE THE HOSTNAME
|--------------------------------------------------------------------------
*/
$hostname = new Hostname(['fqdn' => $customer['domain']]);
app(HostnameRepository::class)->attach($hostname, $website);

/*
|--------------------------------------------------------------------------
| CREATE THE CUSTOMER
|--------------------------------------------------------------------------
*/
$customer = new Customer(['name' => $customer['name'], 'email' => $customer['email']]);
app(CustomerRepository::class)->create($customer);

/*
|--------------------------------------------------------------------------
| SAVE THE CUSTOMER WITH HIS HOSTNAME AND WEBSITE
|--------------------------------------------------------------------------
*/
$hostname->customer()->associate($customer)->save();
$website->customer()->associate($customer)->save();
}
}
}

<?