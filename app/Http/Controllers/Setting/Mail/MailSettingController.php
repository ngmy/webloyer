<?php

declare(strict_types=1);

namespace App\Http\Controllers\Setting\Mail;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\Mail as MailSettingRequest;
use App\Repositories\Setting\SettingInterface;
use App\Services\Form\Setting\MailSettingForm;

class MailSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('acl');
    }

    public function get(SettingInterface $settingRepository)
    {
        $settings = $settingRepository->byType('mail');

        return view('settings.email')
            ->with('settings', $settings);
    }

    public function post(MailSettingRequest\PostRequest $request, MailSettingForm $mailSettingForm)
    {
        $input = $request->all();

        $mailSettingForm->update($input);

        return redirect()->route('settings.email');
    }
}
