<?php

namespace App\Http\Middleware;

use Closure;
use Ngmy\Webloyer\Webloyer\Application\Setting\SettingService;

class ApplySettings
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $mailSetting = $this->settingService->getMailSetting();

        if (isset($mailSetting->from()['address'])) {
            $fromAddress = $mailSetting->from()['address'];
        } else {
            $fromAddress = null;
        }

        if (isset($mailSetting->from()['name'])) {
            $fromName = $mailSetting->from()['name'];
        } else {
            $fromName = null;
        }

        config(['mail.driver'       => $mailSetting->driver()->value()]);
        config(['mail.from.address' => $fromAddress]);
        config(['mail.from.name'    => $fromName]);
        config(['mail.host'         => $mailSetting->smtpHost()]);
        config(['mail.port'         => $mailSetting->smtpPort()]);
        config(['mail.encryption'   => $mailSetting->smtpEncryption()->value()]);
        config(['mail.username'     => $mailSetting->smtpUsername()]);
        config(['mail.password'     => $mailSetting->smtpPassword()]);
        config(['mail.sendmail'     => $mailSetting->sendmailPath()]);

        return $next($request);
    }
}
