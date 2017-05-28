<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Ngmy\Webloyer\Webloyer\Application\Setting\SettingService;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\SettingForm\MailSettingForm;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('acl');
    }

    public function getEmail(SettingService $settingService)
    {
        $mailSetting = $settingService->getMailSetting();

        return view('settings.email')
            ->with('mailSetting', $mailSetting);
    }

    public function postEmail(Request $request, MailSettingForm $mailSettingForm)
    {
        $input = $request->all();

        if ($mailSettingForm->update($input)) {
            return redirect()->route('settings.email');
        } else {
            return redirect()->route('settings.email')
                ->withInput()
                ->withErrors($mailSettingForm->errors());
        }
    }
}
