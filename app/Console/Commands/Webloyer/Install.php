<?php

namespace App\Console\Commands\Webloyer;

use Artisan;
use Hash;

use App\Services\Config\ConfigWriterInterface;
use App\Repositories\User\UserInterface;

use Illuminate\Console\Command;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webloyer:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Webloyer';

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
     * @param \App\Services\Config\ConfigWriterInterface $configWriter
     * @param \App\Repositories\User\UserInterface       $userRepository
     * @return void
     */
    public function handle(ConfigWriterInterface $configWriter, UserInterface $userRepository)
    {
        $config['app_url']        = $this->ask(trans('webloyer.enter_webloyer_url'));
        $config['db_host']        = $this->ask(trans('webloyer.enter_db_host'), 'localhost');
        $config['db_database']    = $this->ask(trans('webloyer.enter_db_name'), 'webloyer');
        $config['db_username']    = $this->ask(trans('webloyer.enter_db_username'), 'webloyer');
        $config['db_password']    = $this->ask(trans('webloyer.enter_db_password'), false);
        $config['admin_name']     = $this->ask(trans('webloyer.enter_admin_name'));
        $config['admin_email']    = $this->ask(trans('webloyer.enter_admin_email'));
        $config['admin_password'] = $this->ask(trans('webloyer.enter_admin_password'));

        // Set configuration to .env
        $configWriter->setConfig('APP_URL',     $config['app_url']);
        $configWriter->setConfig('DB_HOST',     $config['db_host']);
        $configWriter->setConfig('DB_DATABASE', $config['db_database']);
        $configWriter->setConfig('DB_USERNAME', $config['db_username']);
        $configWriter->setConfig('DB_PASSWORD', $config['db_password']);

        // Migrate and seed database
        Artisan::call('migrate:refresh', [
            '--force'          => true,
            '--no-interaction' => true,
        ]);

        Artisan::call('db:seed', [
            '--force'          => true,
            '--no-interaction' => true,
            '--class'          => 'RecipeTableSeeder',
        ]);
        Artisan::call('db:seed', [
            '--force'          => true,
            '--no-interaction' => true,
            '--class'          => 'RoleTableSeeder',
        ]);
        Artisan::call('db:seed', [
            '--force'          => true,
            '--no-interaction' => true,
            '--class'          => 'PermissionTableSeeder',
        ]);
        Artisan::call('db:seed', [
            '--force'          => true,
            '--no-interaction' => true,
            '--class'          => 'PermissionRoleTableSeeder',
        ]);

        // Create admin user
        $admin['name']     = $config['admin_name'];
        $admin['email']    = $config['admin_email'];
        $admin['password'] = Hash::make($config['admin_password']);

        $user = $userRepository->create($admin);
        $user->assignRole('administrator');
    }
}
