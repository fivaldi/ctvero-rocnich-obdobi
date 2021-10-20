<?php

use App\Http\Utilities;

class ApiV0Test extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->get('/');

        $this->sessionSafeToken = Utilities::getSessionSafeToken();
    }

    public function testApiV0BasicUrl()
    {
        $this->get('/api/v0');
        $this->seeStatusCode(404);
    }

    public function testApiV0NonExistingEndpoint()
    {
        $this->get('/api/v0/nonexisting/endpoint');
        $this->seeStatusCode(404);
    }

    public function testApiV0AppMigrateEndpointWithoutAuth()
    {
        $this->get('/api/v0/app/migrate');
        $this->seeStatusCode(403);
    }

    public function testApiV0AppMigrateEndpointWithAuth()
    {
        $this->get('/api/v0/app/migrate', $headers = [ 'X-Ctvero-API-Admin-Secret' => config('ctvero.apiAdminSecret') ]);
        $this->response->assertSeeText('0');
        $this->seeStatusCode(200);
    }

    public function testApiV0UtilGpsToLocatorMissingSessionSafeToken()
    {
        $this->post('/api/v0/util/gpsToLocator', []);
        $this->seeStatusCode(403);
    }

    public function testApiV0UtilGpsToLocatorMissingGpsLonLat()
    {
        $this->post('/api/v0/util/gpsToLocator', [
            '_token' => $this->sessionSafeToken
        ]);
        $this->seeJSON([
            'errors' => [
                'validation.required',
                'validation.required'
            ]
        ]);
        $this->seeStatusCode(422);
    }

    public function testApiV0UtilGpsToLocatorGpsLonLatOutOfRange()
    {
        $this->post('/api/v0/util/gpsToLocator', [
            '_token' => $this->sessionSafeToken,
            'gpsLon' => 180.1,
            'gpsLat' => 90.1,
        ]);
        $this->seeJSON([
            'errors' => [
                'validation.between.numeric',
                'validation.between.numeric'
            ]
        ]);
        $this->seeStatusCode(422);

        $this->post('/api/v0/util/gpsToLocator', [
            '_token' => $this->sessionSafeToken,
            'gpsLon' => -180.1,
            'gpsLat' => -90.1,
        ]);
        $this->seeJSON([
            'errors' => [
                'validation.between.numeric',
                'validation.between.numeric'
            ]
        ]);
        $this->seeStatusCode(422);
    }

    public function testApiV0UtilGpsToLocator()
    {
        $this->post('/api/v0/util/gpsToLocator', [
            '_token' => $this->sessionSafeToken,
            'gpsLon' => 17.2490453,
            'gpsLat' => 49.8127925
        ]);
        $this->seeJSON([
            'gpsLon' => 17.2490453,
            'gpsLat' => 49.8127925,
            'qthLocator' => 'JN89OT'
        ]);
        $this->seeStatusCode(200);
    }

    public function testApiV0UtilLocatorToGpsMissingSessionSafeToken()
    {
        $this->post('/api/v0/util/locatorToGps', []);
        $this->seeStatusCode(403);
    }

    public function testApiV0UtilLocatorToGpsMissingQthLocator()
    {
        $this->post('/api/v0/util/locatorToGps', [
            '_token' => $this->sessionSafeToken
        ]);
        $this->seeJSON([
            'errors' => [
                'validation.required'
            ]
        ]);
        $this->seeStatusCode(422);
    }

    public function testApiV0UtilLocatorToGpsInvalidLocator()
    {
        $this->post('/api/v0/util/locatorToGps', [
            '_token' => $this->sessionSafeToken,
            'qthLocator' => 'XXXXXX'
        ]);
        $this->seeJSON([
            'errors' => [
                'Pole qth locator neobsahuje platný QTH lokátor.'
            ]
        ]);
        $this->seeStatusCode(422);
    }

    public function testApiV0UtilLocatorToGps()
    {
        $this->post('/api/v0/util/locatorToGps', [
            '_token' => $this->sessionSafeToken,
            'qthLocator' => 'JN89OT'
        ]);
        $this->seeJSON([
            'qthLocator' => 'JN89OT',
            'gpsLon' => 17.208333333333336,
            'gpsLat' => 49.8125
        ]);
        $this->seeStatusCode(200);
    }
}
