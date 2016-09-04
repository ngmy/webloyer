<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\Setting\SettingInterface;

class ApplySettings
{
    protected $settingRepository;

    public function __construct(SettingInterface $settingRepository)
    {
        $this->settingRepository = $settingRepository;
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
        $mailSettings = $this->settingRepository->byType('mail');

        if (isset($mailSettings->attributes->getFrom()['address'])) {
            $fromAddress = $mailSettings->attributes->getFrom()['address'];
        } else {
            $fromAddress = null;
        }

        if (isset($mailSettings->attributes->getFrom()['name'])) {
            $fromName = $mailSettings->attributes->getFrom()['name'];
        } else {
            $fromName = null;
        }

        config(['mail.driver'       => $mailSettings->attributes->getDriver()]);
        config(['mail.from.address' => $fromAddress]);
        config(['mail.from.name'    => $fromName]);
        config(['mail.host'         => $mailSettings->attributes->getSmtpHost()]);
        config(['mail.port'         => $mailSettings->attributes->getSmtpPort()]);
        config(['mail.encryption'   => $mailSettings->attributes->getSmtpEncryption()]);
        config(['mail.username'     => $mailSettings->attributes->getSmtpUsername()]);
        config(['mail.password'     => $mailSettings->attributes->getSmtpPassword()]);
        config(['mail.sendmail'     => $mailSettings->attributes->getSendmailPath()]);

        return $next($request);
    }
}
