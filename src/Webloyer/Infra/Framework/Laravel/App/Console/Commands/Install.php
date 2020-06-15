<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Console\Commands;

use Illuminate\Support\Facades\{
    Artisan,
    DB,
    Hash,
    Schema,
};
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Jackiedo\DotenvEditor\DotenvEditor;
use Webloyer\App\Service\User\{
    CreateUserRequest,
    CreateUserService,
};
use Webloyer\Infra\Framework\Laravel\Database\Seeds\DatabaseSeeder;

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
     * @param CreateUserService $createUserService
     * @return void
     */
    public function handle(
        CreateUserService $createUserService,
        DotenvEditor $dotenvEditor
    ) {
        $env['APP_URL'] = $this->ask(trans('webloyer::install.enter_webloyer_url'));

        $env['DB_CONNECTION'] = $this->choice(trans('webloyer::install.enter_db_system'), [
            'mysql'  => 'MySQL',
            'pgsql'  => 'Postgres',
            'sqlite' => 'SQLite',
            'sqlsrv' => 'SQL Server',
        ], 'mysql');

        if ($env['DB_CONNECTION'] == 'sqlite') {
            $env['DB_HOST']     = null;
            $env['DB_DATABASE'] = $this->ask(trans('webloyer::install.enter_db_name_sqlite'), database_path('webloyer.sqlite'));
            $env['DB_USERNAME'] = null;
            $env['DB_PASSWORD'] = null;
        } else {
            $env['DB_HOST']     = $this->ask(trans('webloyer::install.enter_db_host'), 'localhost');
            $env['DB_DATABASE'] = $this->ask(trans('webloyer::install.enter_db_name'), 'webloyer');
            $env['DB_USERNAME'] = $this->ask(trans('webloyer::install.enter_db_username'), 'webloyer');
            $env['DB_PASSWORD'] = $this->ask(trans('webloyer::install.enter_db_password'));
        }

        // Set the env buffer to the config in the current request
        config(['database.default' => $env['DB_CONNECTION']]);
        if ($env['DB_CONNECTION'] == 'sqlite') {
            config(['database.connections.' . $env['DB_CONNECTION'] . '.database' => $env['DB_DATABASE']]);
        } else {
            config(['database.connections.' . $env['DB_CONNECTION'] . '.host'     => $env['DB_HOST']]);
            config(['database.connections.' . $env['DB_CONNECTION'] . '.database' => $env['DB_DATABASE']]);
            config(['database.connections.' . $env['DB_CONNECTION'] . '.username' => $env['DB_USERNAME']]);
            config(['database.connections.' . $env['DB_CONNECTION'] . '.password' => $env['DB_PASSWORD']]);
        }

        DB::transaction(function () use ($env, $createUserService, $dotenvEditor) {
            // Create the database if the database is empty or you want to re-create
            $migrationsTable = config('database.migrations');
            $isDbEmpty = !Schema::hasTable($migrationsTable) || DB::table($migrationsTable)->count() == 0;
            if ($isDbEmpty || $this->confirm(trans('webloyer::confirm_recreate_db'))) {
                $admin['name']     = $this->ask(trans('webloyer::install.enter_admin_name'));
                $admin['email']    = $this->ask(trans('webloyer::install.enter_admin_email'));
                $admin['password'] = $this->ask(trans('webloyer::install.enter_admin_password'));

                // Rollback and re-migrate
                Artisan::call('migrate:refresh', [
                    '--force'          => true,
                    '--no-interaction' => true,
                ]);

                // Insert data. Nothing is inserted into the user table because the admin user is already inserted
                Artisan::call('db:seed', [
                    '--force'          => true,
                    '--no-interaction' => true,
                    '--class'          => DatabaseSeeder::class,
                ]);

                // Create the admin user
                $createUserRequest = (new CreateUserRequest())
                    ->setEmail($admin['email'])
                    ->setName($admin['name'])
                    ->setPassword(Hash::make($admin['password']))
                    ->setApiToken(Str::random(60))
                    ->setRoles(['administrator']);
                $createUserService->execute($createUserRequest);
            }

            // Save the env buffer to the .env file
            foreach ($env as $key => $value) {
                $dotenvEditor->setKey($key, $value);
            }
            $dotenvEditor->save();
        });
    }
}
