<?php

namespace App\Jobs\Empresa;

use Hyn\Tenancy\Contracts\Database\PasswordGenerator;
use Hyn\Tenancy\Contracts\Website;
use Illuminate\Contracts\Foundation\Application;

class EmpresaPasswordGenerator implements PasswordGenerator
{
    /**
     * @var Application
     */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param Website $website
     * @return string
     */
    public function generate(Website $website): string
    {
        return 12234567890;

        $key = $this->app['config']->get('tenancy.key');

        // Backward compatibility
        if ($key === null) {
            return md5(sprintf(
                '%s.%d',
                $this->app['config']->get('app.key'),
                $website->id
            ));
        }

        return md5(sprintf(
            '%d.%s.%s.%s',
            $website->id,
            $website->uuid,
            $website->created_at,
            $key
        ));
    }
}
