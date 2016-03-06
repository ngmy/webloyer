<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Form\Setting\MailSettingForm;
use App\Repositories\Setting\MailSettingInterface;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('acl');
    }

    public function getEmail(MailSettingInterface $mailSettingRepository)
    {
        $settings = $mailSettingRepository->all();

        return view('settings.email')
            ->with('settings', $settings);
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
