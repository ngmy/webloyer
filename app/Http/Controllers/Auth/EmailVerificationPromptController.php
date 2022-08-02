<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

/**
 * Class EmailVerificationPromptController
 * @package App\Http\Controllers\Auth
 */
class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     *
     * @param Request $request
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(RouteServiceProvider::HOME)
                    : view('auth.verify-email');
    }
}
