<?php

namespace App\Console\Commands\Webloyer;

use Artisan;
use Hash;

use App\Repositories\Setting\AppSettingInterface;
use App\Repositories\Setting\DbSettingInterface;
use App\Repositories\User\UserInterface;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

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
     * @param \App\Repositories\Setting\AppSettingInterface $appSetting
     * @param \App\Repositories\Setting\DbSettingInterface  $dbSetting
     * @param \App\Repositories\User\UserInterface          $userRepository
     * @return void
     */
    public function handle(AppSettingInterface $appSetting, DbSettingInterface $dbSetting, UserInterface $userRepository)
    {
        $config['app']['url'] = $this->ask(trans('webloyer.enter_webloyer_url'));

        $config['db']['driver'] = $this->choice(trans('webloyer.enter_db_system'), [
            'mysql'  => 'MySQL',
            'pgsql'  => 'Postgres',
            'sqlite' => 'SQLite',
            'sqlsrv' => 'SQL Server',
        ], 'mysql');

        if ($config['db']['driver'] !== 'sqlite') {
            $config['db']['host']     = $this->ask(trans('webloyer.enter_db_host'), 'localhost');
            $config['db']['database'] = $this->ask(trans('webloyer.enter_db_name'), 'webloyer');
            $config['db']['username'] = $this->ask(trans('webloyer.enter_db_username'), 'webloyer');
            $config['db']['password'] = $this->ask(trans('webloyer.enter_db_password'), false);
        } else {
            $config['db']['host']     = null;
            $config['db']['database'] = $this->ask(trans('webloyer.enter_db_name_sqlite'), storage_path('webloyer.sqlite'));
            $config['db']['username'] = null;
            $config['db']['password'] = null;
        }

        $config['admin']['name']     = $this->ask(trans('webloyer.enter_admin_name'));
        $config['admin']['email']    = $this->ask(trans('webloyer.enter_admin_email'));
        $config['admin']['password'] = $this->ask(trans('webloyer.enter_admin_password'));

        // Set configuration to .env
        $appSetting->update($config['app']);
        $dbSetting->update($config['db']);

        config(['database.default'                                          => $config['db']['driver']]);
        config(['database.connections.'.$config['db']['driver'].'.host'     => $config['db']['host']]);
        config(['database.connections.'.$config['db']['driver'].'.database' => $config['db']['database']]);
        config(['database.connections.'.$config['db']['driver'].'.username' => $config['db']['username']]);
        config(['database.connections.'.$config['db']['driver'].'.password' => $config['db']['password']]);

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
        $config['admin']['password'] = Hash::make($config['admin']['password']);
        $config['admin']['api_token'] = Str::random(60);

        $user = $userRepository->create($config['admin']);
        $user->assignRole('administrator');
    }
}
