<?php

use App\Http\Utilities;
use App\Models\User;

class ApiV0Test extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->get('/');

        $this->sessionSafeToken = Utilities::getSessionSafeToken();
        $this->usersToDelete = array();
    }

    public function tearDown(): void
    {
        User::whereIn('id', $this->usersToDelete)->delete();

        parent::tearDown();
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

    public function testApiV0AppMigrateEndpointWrongRequestMethod()
    {
        $this->post('/api/v0/app/migrate');
        $this->seeStatusCode(405);
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
                'Pole gps lat je vyžadováno.',
                'Pole gps lon je vyžadováno.'
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
                'Pole gps lat neobsahuje hodnotu v rozmezí od -90 do 90.',
                'Pole gps lon neobsahuje hodnotu v rozmezí od -180 do 180.'
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
                'Pole gps lat neobsahuje hodnotu v rozmezí od -90 do 90.',
                'Pole gps lon neobsahuje hodnotu v rozmezí od -180 do 180.'
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
                'Pole qth locator je vyžadováno.'
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

    public function testApiV0UserNicknameNotLoggedIn()
    {
        $this->post('/api/v0/user/nickname', []);
        $this->seeStatusCode(401);
    }

    public function testApiV0UserNicknameChangeToSame()
    {
        $user = User::factory()->create();
        $user->nickname = Str::random(40);
        array_push($this->usersToDelete, $user->id);

        Auth::login($user);
        $this->post('/api/v0/user/nickname', [
            'nickname' => $user->nickname,
        ]);
        $this->seeJSON([
            'errors' => [ 'Toto je současná přezdívka. Zůstává nezměněna.' ],
        ]);
        $this->seeStatusCode(422);
    }

    public function testApiV0UserNicknameChangeToExisting()
    {
        $user1 = User::factory()->create();
        array_push($this->usersToDelete, $user1->id);
        $user2 = User::factory()->create();
        $user2->nickname = Str::random(40);
        $user2->save();
        array_push($this->usersToDelete, $user2->id);

        Auth::login($user1);
        $this->post('/api/v0/user/nickname', [
            'nickname' => $user2->nickname,
        ]);
        $this->seeJSON([
            'errors' => [ 'Pole nickname již obsahuje v databázi stejný záznam.' ],
        ]);
        $this->seeStatusCode(422);
    }

    public function testApiV0UserNicknameChangeToEmpty()
    {
        $user = User::factory()->create();
        $user->nickname = Str::random(40);
        array_push($this->usersToDelete, $user->id);

        Auth::login($user);
        $this->post('/api/v0/user/nickname', [
            'nickname' => '',
        ]);
        $this->seeJSON([
            'errors' => [ 'Pole nickname je vyžadováno.' ],
        ]);
        $this->seeStatusCode(422);
    }

    public function testApiV0UserNicknameChange()
    {
        $user = User::factory()->create();
        array_push($this->usersToDelete, $user->id);

        Auth::login($user);
        $this->post('/api/v0/user/nickname', [
            'nickname' => Str::random(40),
        ]);
        $this->seeJSON([ 'success' ]);
        $this->seeStatusCode(200);
    }
}
