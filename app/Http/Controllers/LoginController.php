<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

use App\Exceptions\AppException;
use App\Http\Utilities;
use App\Models\User;

class LoginController extends Controller
{
    public function loginChecks($provider)
    {
        $registeredProviders = [
            'facebook',
            'google',
            'twitter',
        ];
        if (! in_array($provider, $registeredProviders)) {
            throw new AppException(422, array(__('Neznámý nebo nepodporovaný poskytovatel autentizace: :provider', [ 'provider' => $provider ])));
        }
        if (Auth::check()) {
            Session::flash('infos', array(__('Uživatelský účet je již přihlášen.')));
            return Utilities::smartRedirect();
        }
    }

    public function login($provider)
    {
        $redirect = $this->loginChecks($provider);
        if ($redirect) {
            return $redirect;
        }
        Session::put('redirectUrlAfterLogin', request()->header('referer'));

        return Socialite::with($provider)->redirect();
    }

    public function callback($provider)
    {
        $redirect = $this->loginChecks($provider);
        if ($redirect) {
            return $redirect;
        }

        try {
            $oauthUser = Socialite::driver($provider)->user();
            Log::debug('Received OAuth user:', [ var_export($oauthUser, true) ]);
        } catch (\Exception $e) {
            Log::error('Error with OAuth provider:', [ $provider ]);
            throw new AppException(503, array(__('Chyba poskytovatele autentizace: :provider', [ 'provider' => $provider ])));
        }

        $authInfo = [ 'name' => $oauthUser->getName(), 'email' => $oauthUser->getEmail() ];
        $user = User::where($authInfo)->first();
        if (! $user) {
            Log::info('Create new account for:', [ var_export($authInfo, true) ]);
            $user = User::create([
                'name' => $oauthUser->getName(),
                'email' => $oauthUser->getEmail()
            ]);
            $user->save();
        }
        $user->providers()->updateOrCreate([
            'provider' => $provider,
        ], [
            'provider_uid' => $oauthUser->getId(),
            'avatar_url' => $oauthUser->getAvatar(),
        ]);
        $user->save();

        Auth::login($user);
        Log::info('Login successful for:', [ var_export($authInfo, true) ]);
        Session::flash('successes', array(__('Přihlášení proběhlo úspěšně.')));
        Session::regenerateToken();

        return Utilities::smartRedirect(Session::pull('redirectUrlAfterLogin', NULL));
    }

    public function logout()
    {
        if (! Auth::check()) {
            Session::flash('infos', array(__('Uživatelský účet nebyl přihlášen.')));
            return Utilities::smartRedirect();
        }

        $userInfo = [ Auth::user()->name, Auth::user()->email ];
        Auth::logout();
        Log::info('Logout successful for:', [ var_export($userInfo, true) ]);
        Session::flash('successes', array(__('Odhlášení proběhlo úspěšně.')));
        Session::regenerateToken();

        return Utilities::smartRedirect();
    }
}
