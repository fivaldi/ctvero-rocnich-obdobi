<?php

class BasicTest extends TestCase
{
    public function setUp(): void {
        parent::setUp();

        $this->sessionCookieName = 'ctvero_rocnich_obdobi_session';
    }

    public function testIndex()
    {
        $this->get('/');
        $this->response->assertSeeTextInOrder([
            'Čtvero ročních období',
            'CB soutěž',
        ]);
        $this->seeStatusCode(200);
    }

    public function testIndexCsLocale()
    {
        $this->get('/', $headers = [ 'Accept-Language' => 'cs' ]);
        $this->response->assertSeeTextInOrder([
            'Čtvero ročních období',
            'CB soutěž',
        ]);
        $this->seeStatusCode(200);
    }

    public function testIndexDeLocale()
    {
        $this->get('/', $headers = [ 'Accept-Language' => 'de-AT' ]);
        $this->response->assertSeeTextInOrder([
            'Die vier Jahreszeiten',
            'CB-Wettbewerb',
        ]);
        $this->seeStatusCode(200);
    }

    public function testIndexMultipleLocalesDePreferred()
    {
        $this->get('/', $headers = [ 'Accept-Language' => 'de-AT,cs' ]);
        $this->response->assertSeeTextInOrder([
            'Die vier Jahreszeiten',
            'CB-Wettbewerb',
        ]);
        $this->seeStatusCode(200);
    }

    public function testIndexFallbackLocale()
    {
        $this->get('/', $headers = [ 'Accept-Language' => 'unsupported' ]);
        $this->response->assertSeeTextInOrder([
            'Čtvero ročních období',
            'CB soutěž',
        ]);
        $this->seeStatusCode(200);
    }

    public function testSwitchLocaleToCs()
    {
        $this->get('/lang/cs');
        $sessionCookie = $this->response->getCookie($this->sessionCookieName);
        $this->get('/', $headers = [ 'Set-Cookie' => $this->sessionCookieName . '=' . $sessionCookie ]);
        $this->response->assertSeeTextInOrder([
            'Čtvero ročních období',
            'CB soutěž',
        ]);
        $this->seeStatusCode(200);
    }

    public function testSwitchLocaleToDe()
    {
        $this->get('/lang/de');
        $sessionCookie = $this->response->getCookie($this->sessionCookieName);
        $this->get('/', $headers = [ 'Set-Cookie' => $this->sessionCookieName . '=' . $sessionCookie ]);
        $this->response->assertSeeTextInOrder([
            'Die vier Jahreszeiten',
            'CB-Wettbewerb',
        ]);
        $this->seeStatusCode(200);
    }

    public function testSwitchLocaleToUnsupported()
    {
        $this->get('/lang/unsupported');
        $this->seeStatusCode(422);
    }

    public function test404()
    {
        $this->get('/non-existing-page');
        $this->seeStatusCode(404);
    }

    public function testResults()
    {
        $this->get('/vysledky');
        $this->seeStatusCode(200);
    }

    public function testSubmissionForm()
    {
        $this->get('/hlaseni');
        $this->seeStatusCode(200);
    }

    public function testSubmissionFormInvalidStep()
    {
        $this->get('/hlaseni?krok=999999999');
        $this->response->assertSeeText('Neplatný formulářový krok');
        $this->seeStatusCode(422);
    }

    public function testCalendar()
    {
        $this->get('/kalendar?soutez=Předkolo Zima 2020');
        $this->response->assertDownload();
        $this->seeStatusCode(200);
    }

    public function testCalendarBadRequest()
    {
        $this->get('/kalendar');
        $this->seeStatusCode(400);
    }

    public function testCalendarNonExistingContest()
    {
        $this->get('/kalendar?soutez=Neexistující soutěž');
        $this->seeStatusCode(404);
    }

    public function testRecaptchaOnMessageSend()
    {
        if (! config('ctvero.recaptchaSiteKey')) {
            $this->markTestSkipped('ReCAPTCHA is not configured.');
        }
        $originalConfig = config('app.env');
        config([ 'app.env' => 'production' ]);
        $this->post('/message', []);
        $this->seeStatusCode(403);
        config([ 'app.env' => $originalConfig ]);
    }

    public function testWrongMethodOnMessageSend()
    {
        $this->get('/message');
        $this->seeStatusCode(405);
    }

    public function testRecaptchaOnSubmissionSend()
    {
        if (! config('ctvero.recaptchaSiteKey')) {
            $this->markTestSkipped('ReCAPTCHA is not configured.');
        }
        $originalConfig = config('app.env');
        config([ 'app.env' => 'production' ]);
        $this->post('/submission', [ 'step' => 1 ]);
        $this->seeStatusCode(403);
        config([ 'app.env' => $originalConfig ]);
    }

    public function testRecaptchaOnSubmissionSendStepTwo()
    {
        if (! config('ctvero.recaptchaSiteKey')) {
            $this->markTestSkipped('ReCAPTCHA is not configured.');
        }
        $originalConfig = config('app.env');
        config([ 'app.env' => 'production' ]);
        $this->post('/submission', [ 'step' => 2 ]);
        $this->seeStatusCode(403);
        config([ 'app.env' => $originalConfig ]);
    }

    public function testWrongMethodOnSubmissionSend()
    {
        $this->get('/submission');
        $this->seeStatusCode(405);
    }
}
