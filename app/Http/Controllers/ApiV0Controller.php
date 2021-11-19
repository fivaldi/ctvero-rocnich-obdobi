<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

use App\Exceptions\ForbiddenException;
use App\Http\Utilities;
use App\Rules\Locator;

class ApiV0Controller extends Controller
{
    function route($category, $endpoint)
    {
        $registeredMethods = [
            'appMigrate',
            'userNickname',
            'utilGpsToLocator',
            'utilLocatorToGps',
        ];
        $method = Str::camel($category . '_' . $endpoint);
        if (! in_array($method, $registeredMethods)) {
            throw new NotFoundHttpException();
        }
        $method .= request()->method();
        if (! method_exists($this, $method)) {
            throw new MethodNotAllowedHttpException([]);
        }

        return $this->$method();
    }

    public function appMigrateGET()
    {
        if (strlen(config('ctvero.apiAdminSecret', '')) < 32 or request()->header('X-Ctvero-API-Admin-Secret') !== config('ctvero.apiAdminSecret')) {
            throw new ForbiddenException();
        }

        return Artisan::call('migrate');
    }

    public function userNicknamePOST()
    {
        if (! Auth::check()) {
            throw new UnauthorizedHttpException('');
        }

        $nickname = trim(request()->input('nickname'));

        $user = Auth::user();
        if ($nickname == $user->nickname) {
            return response()->json([ 'errors' => array(__('Toto je současná přezdívka. Zůstává nezměněna.')) ])->setStatusCode(422);
        }

        $validator = Validator::make([ 'nickname' => $nickname ], [
            'nickname' => 'required|max:255|unique:\App\Models\User,nickname',
        ], Utilities::validatorMessages());
        if ($validator->fails()) {
            return response()->json([ 'errors' => $validator->messages()->all() ])->setStatusCode(422);
        }

        $user->nickname = $nickname;
        $user->save();
        return response()->json([ 'success' ]);
    }

    public function utilGpsToLocatorPOST()
    {
        Utilities::validateSessionSafeToken();

        $validator = Validator::make(request()->all(), [
            'gpsLon' => 'required|numeric|between:-180,180',
            'gpsLat' => 'required|numeric|between:-90,90',
        ], Utilities::validatorMessages());
        if ($validator->fails()) {
            return response()->json([ 'errors' => $validator->messages()->all() ])->setStatusCode(422);
        }

        $gpsLon = request()->input('gpsLon');
        $gpsLat = request()->input('gpsLat');
        return response()->json([ 'gpsLon' => $gpsLon,
                                  'gpsLat' => $gpsLat,
                                  'qthLocator' => Utilities::gpsToLocator($gpsLon, $gpsLat) ]);
    }

    public function utilLocatorToGpsPOST()
    {
        Utilities::validateSessionSafeToken();

        $validator = Validator::make(request()->all(), [
            'qthLocator' => [ 'required', 'size:6', new Locator ],
        ], Utilities::validatorMessages());
        if ($validator->fails()) {
            return response()->json([ 'errors' => $validator->messages()->all() ])->setStatusCode(422);
        }

        list($gpsLon, $gpsLat) = Utilities::locatorToGps(request()->input('qthLocator'));
        return response()->json([ 'qthLocator' => request()->input('qthLocator'),
                                  'gpsLon' => $gpsLon,
                                  'gpsLat' => $gpsLat ]);
    }
}
