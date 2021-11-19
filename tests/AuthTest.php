<?php

use Illuminate\Support\Facades\Auth;

use App\Models\User;

class AuthTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->usersToDelete = array();
    }

    public function tearDown(): void
    {
        User::whereIn('id', $this->usersToDelete)->delete();

        parent::tearDown();
    }

    public function testLoginFacebook()
    {
        $this->get('/login/facebook');
        $this->seeStatusCode(302);
        $this->response->assertSeeTextInOrder([
            'Redirecting to ',
            'facebook.com',
            'oauth',
        ]);
    }

    public function testLoginFacebookCallbackError()
    {
        $this->get('/login/facebook/callback');
        $this->seeStatusCode(503);
        $this->response->assertSeeText('Chyba poskytovatele autentizace: facebook');
    }

    public function testLoginGoogle()
    {
        $this->get('/login/google');
        $this->seeStatusCode(302);
        $this->response->assertSeeTextInOrder([
            'Redirecting to ',
            'google.com',
            'oauth',
        ]);
    }

    public function testLoginGoogleCallbackError()
    {
        $this->get('/login/google/callback');
        $this->seeStatusCode(503);
        $this->response->assertSeeText('Chyba poskytovatele autentizace: google');
    }

    public function testLoginTwitter()
    {
        if (! config('services.twitter.client_id')) {
            $this->markTestSkipped('Twitter OAuth is not configured.');
        }
        $this->get('/login/twitter');
        $this->seeStatusCode(302);
        $this->response->assertSeeTextInOrder([
            'Redirecting to ',
            'twitter.com',
            'oauth',
        ]);
    }

    public function testLoginTwitterCallbackError()
    {
        $this->get('/login/twitter/callback');
        $this->seeStatusCode(503);
        $this->response->assertSeeText('Chyba poskytovatele autentizace: twitter');
    }

    public function testUnsupportedAuthProvider()
    {
        $this->get('/login/unsupported/callback');
        $this->seeStatusCode(422);
        $this->response->assertSeeText('Neznámý nebo nepodporovaný poskytovatel autentizace: unsupported');
    }

    public function testAlreadyLoggedIn()
    {
        $user = User::factory()->create();
        array_push($this->usersToDelete, $user->id);

        $this->get('/');
        Auth::login($user);
        $this->get('/login/google');
        $this->seeStatusCode(302);
        $this->get('/');
        $this->response->assertSeeText('Uživatelský účet je již přihlášen.');
    }

    public function testAlreadyLoggedInOnCallback()
    {
        $user = User::factory()->create();
        array_push($this->usersToDelete, $user->id);

        $this->get('/');
        Auth::login($user);
        $this->get('/login/google/callback');
        $this->seeStatusCode(302);
        $this->get('/');
        $this->response->assertSeeText('Uživatelský účet je již přihlášen.');
    }

    public function testLogout()
    {
        $user = User::factory()->create();
        array_push($this->usersToDelete, $user->id);

        $this->get('/');
        Auth::login($user);
        $this->get('/logout');
        $this->seeStatusCode(302);
        $this->get('/');
        $this->response->assertSeeText('Odhlášení proběhlo úspěšně.');
    }

    public function testLogoutWithoutLogin()
    {
        $this->get('/logout');
        $this->seeStatusCode(302);
        $this->get('/');
        $this->response->assertSeeText('Uživatelský účet nebyl přihlášen.');
    }
}
