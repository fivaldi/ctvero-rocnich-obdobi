<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Laravel\Lumen\Routing\Controller as BaseController;

use App\Exceptions\ForbiddenException;

class ApiV0Controller extends BaseController
{
    public function appMigrate()
    {
        if (strlen(config('ctvero.apiAdminSecret', '')) < 32 or request()->header('X-Ctvero-API-Admin-Secret') !== config('ctvero.apiAdminSecret')) {
            throw new ForbiddenException();
        }

        return Artisan::call('migrate');
    }
}
