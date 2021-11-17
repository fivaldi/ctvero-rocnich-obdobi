<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;

use App\Exceptions\ForbiddenException;
use App\Http\Utilities;
use App\Rules\Locator;

class ApiV0Controller extends BaseController
{
    public function appMigrate()
    {
        if (strlen(config('ctvero.apiAdminSecret', '')) < 32 or request()->header('X-Ctvero-API-Admin-Secret') !== config('ctvero.apiAdminSecret')) {
            throw new ForbiddenException();
        }

        return Artisan::call('migrate');
    }

    public function utilGpsToLocator()
    {
        Utilities::validateSessionSafeToken();

        $validator = Validator::make(request()->all(), [
            'gpsLon' => 'required|numeric|between:-180,180',
            'gpsLat' => 'required|numeric|between:-90,90',
        ]);
        if ($validator->fails()) {
            return response()->json([ 'errors' => $validator->messages()->all() ])->setStatusCode(422);
        }

        $gpsLon = request()->input('gpsLon');
        $gpsLat = request()->input('gpsLat');
        return response()->json([ 'gpsLon' => $gpsLon,
                                  'gpsLat' => $gpsLat,
                                  'qthLocator' => Utilities::gpsToLocator($gpsLon, $gpsLat) ]);
    }

    public function utilLocatorToGps()
    {
        Utilities::validateSessionSafeToken();

        $validator = Validator::make(request()->all(), [
            'qthLocator' => [ 'required', 'size:6', new Locator ],
        ]);
        if ($validator->fails()) {
            return response()->json([ 'errors' => $validator->messages()->all() ])->setStatusCode(422);
        }

        list($gpsLon, $gpsLat) = Utilities::locatorToGps(request()->input('qthLocator'));
        return response()->json([ 'qthLocator' => request()->input('qthLocator'),
                                  'gpsLon' => $gpsLon,
                                  'gpsLat' => $gpsLat ]);
    }
}
