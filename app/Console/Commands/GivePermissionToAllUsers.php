<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class GivePermissionToAllUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system_task:user_permisos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Darle todos los permisos a los usuarios dueños de la empresa';

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
        $users = User::owners()->get();

        foreach( $users as $user ){
            $user->giveAllPermission();
        } 
    }
}
