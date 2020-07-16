<?php

namespace App\Console\Commands;

use Artisan;
use Hash;
use Illuminate\Console\Command;
use Ngmy\Webloyer\Webloyer\Application\Setting\SettingService;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\DbSettingDriver;
use Ngmy\Webloyer\IdentityAccess\Application\User\UserService;
use Ngmy\Webloyer\IdentityAccess\Application\Role\RoleService;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleSlug;
use PermissionRoleTableSeeder;
use PermissionTableSeeder;
use RecipeTableSeeder;
use RoleTableSeeder;

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
     * @param \Ngmy\Webloyer\Webloyer\Application\Setting\SettingService $settingService
     * @param \Ngmy\Webloyer\IdentityAccess\Application\User\UserService $userService
     * @param \Ngmy\Webloyer\IdentityAccess\Application\Role\RoleService $roleService
     * @return void
     */
    public function handle(SettingService $settingService, UserService $userService, RoleService $roleService)
    {
        $appSettingUrl = $this->ask(trans('webloyer.enter_webloyer_url'));

        $dbSettingDriverMysql     = DbSettingDriver::mysql();
        $dbSettingDriverPostgres  = DbSettingDriver::postgres();
        $dbSettingDriverSqlite    = DbSettingDriver::sqlite();
        $dbSettingDriverSqlServer = DbSettingDriver::sqlServer();

        $dbSettingDriver = new DbSettingDriver(
            $this->choice(trans('webloyer.enter_db_system'), [
                $dbSettingDriverMysql->value()     => $dbSettingDriverMysql->displayName(),
                $dbSettingDriverPostgres->value()  => $dbSettingDriverPostgres->displayName(),
                $dbSettingDriverSqlite->value()    => $dbSettingDriverSqlite->displayName(),
                $dbSettingDriverSqlServer->value() => $dbSettingDriverSqlServer->displayName(),
            ], $dbSettingDriverMysql->value())
        );

        if (!$dbSettingDriver->isSqlite()) {
            $dbSettingHost     = $this->ask(trans('webloyer.enter_db_host'), 'localhost');
            $dbSettingDatabase = $this->ask(trans('webloyer.enter_db_name'), 'webloyer');
            $dbSettingUserName = $this->ask(trans('webloyer.enter_db_username'), 'webloyer');
            $dbSettingPassword = $this->ask(trans('webloyer.enter_db_password'), false);
        } else {
            $dbSettingHost     = null;
            $dbSettingDatabase = $this->ask(trans('webloyer.enter_db_name_sqlite'), storage_path('webloyer.sqlite'));
            $dbSettingUserName = null;
            $dbSettingPassword = null;
        }

        $adminName     = $this->ask(trans('webloyer.enter_admin_name'));
        $adminEmail    = $this->ask(trans('webloyer.enter_admin_email'));
        $adminPassword = $this->ask(trans('webloyer.enter_admin_password'));

        // Set application configuration to .env
        $settingService->saveAppSetting($appSettingUrl);

        // Set database configuration to .env
        $settingService->saveDbSetting(
            $dbSettingDriver->value(),
            $dbSettingHost,
            $dbSettingDatabase,
            $dbSettingUserName,
            $dbSettingPassword
        );
        $dbSetting = $settingService->getDbSetting();

        config(['database.default'                                                    => $dbSetting->driver()->value()]);
        config(['database.connections.' . $dbSetting->driver()->value() . '.host'     => $dbSetting->host()]);
        config(['database.connections.' . $dbSetting->driver()->value() . '.database' => $dbSetting->database()]);
        config(['database.connections.' . $dbSetting->driver()->value() . '.username' => $dbSetting->userName()]);
        config(['database.connections.' . $dbSetting->driver()->value() . '.password' => $dbSetting->password()]);

        // Migrate and seed database
        Artisan::call('migrate:refresh', [
            '--force'          => true,
            '--no-interaction' => true,
        ]);

        Artisan::call('db:seed', [
            '--force'          => true,
            '--no-interaction' => true,
            '--class'          => RecipeTableSeeder::class,
        ]);
        Artisan::call('db:seed', [
            '--force'          => true,
            '--no-interaction' => true,
            '--class'          => RoleTableSeeder::class,
        ]);
        Artisan::call('db:seed', [
            '--force'          => true,
            '--no-interaction' => true,
            '--class'          => PermissionTableSeeder::class,
        ]);
        Artisan::call('db:seed', [
            '--force'          => true,
            '--no-interaction' => true,
            '--class'          => PermissionRoleTableSeeder::class,
        ]);

        // Create admin user
        $adminHashedPassword = Hash::make($adminPassword);
        $adminApiToken = str_random(60);

        $adminRole = $roleService->getRoleBySlug(RoleSlug::administrator()->value());
        $adminRoleIds = [
            $adminRole->roleId()->id(),
        ];
        $adminUser = $userService->saveUser(
            null,
            $adminName,
            $adminEmail,
            $adminHashedPassword,
            $adminApiToken,
            $adminRoleIds,
            null
        );
    }
}
