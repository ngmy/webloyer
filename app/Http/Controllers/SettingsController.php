<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Services\Form\Setting\MailSettingForm;
use App\Repositories\Setting\SettingInterface;

/**
 * Class SettingsController
 * @package App\Http\Controllers
 */
class SettingsController extends Controller
{
    /**
     * SettingsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('acl');
    }

    /**
     * @param SettingInterface $settingRepository
     * @return Factory|View
     */
    public function getEmail(SettingInterface $settingRepository)
    {
        $settings = $settingRepository->byType('mail');
        return view('settings.email')
            ->with('settings', $settings);
    }

    /**
     * @param Request $request
     * @param MailSettingForm $mailSettingForm
     * @return \Illuminate\Http\RedirectResponse
     */
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
