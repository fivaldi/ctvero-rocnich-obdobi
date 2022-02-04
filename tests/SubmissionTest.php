<?php

use DiDom\Document;

use App\Models\Category;
use App\Models\Contest;
use App\Models\Diary;
use App\Models\User;

class SubmissionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->get('/lang/cs');

        $this->contest = Contest::factory()->create();
        $this->seeInDatabase('contest', [ 'name' => $this->contest->name ]);
        $this->usersToDelete = array();

        $this->get('/submission');

        // CSRF
        $doc = new Document($this->response->getContent(), false);
        $this->csrfToken = $doc->first('form#submission-form input[name=_csrf]')->value;
    }

    public function tearDown(): void
    {
        Diary::where('call_sign', 'like', 'Test call sign%')->delete();
        Contest::where('name', 'like', 'Test contest %')->delete();
        User::whereIn('id', $this->usersToDelete)->delete();

        parent::tearDown();
    }

    public function testSubmissionManual()
    {
        $category = Category::all()->where('name', 'Pěšák')->first();

        $this->get('/contests');
        $this->response->assertSeeText($this->contest->name);

        $this->get('/results');
        $this->response->assertDontSeeText($this->contest->name);

        $this->post('/submission', [
            '_csrf' => $this->csrfToken,
            'step' => 2,
            'contest' => $this->contest->name,
            'category' => $category->name,
            'diaryUrl' => 'https://example.com/diary/url',
            'callSign' => 'Test call sign',
            'qthName' => 'Test QTH name',
            'qthLocator' => 'jn79so',
            'qsoCount' => '99',
            'email' => 'name@example.com'
        ]);
        $this->seeInDatabase('diary', [
            'contest_id' => $this->contest->id,
            'category_id' => $category->id,
            'user_id' => NULL,
            'diary_url' => 'https://example.com/diary/url',
            'call_sign' => 'Test call sign',
            'qth_name' => 'Test QTH name',
            'qth_locator' => 'JN79SO',
            'qth_locator_lon' => '15.541667',
            'qth_locator_lat' => '49.604167',
            'qso_count' => '99',
            'email' => 'name@example.com'
        ]);
        $this->get('/submission?step=2');
        $this->response->assertSeeText('Hlášení do soutěže ' . $this->contest->name . ' bylo úspěšně zpracováno.');

        $this->get('/results');
        $this->response->assertSeeTextInOrder([
            $this->contest->name,
            ' (průběžné pořadí)',
            '1.',
            'Test call sign',
            'Test QTH name',
            'JN79SO',
            '99',
        ]);

        $this->get('/contest/' . $this->contest->name);
        $this->response->assertSeeText('Výsledková listina (průběžné pořadí)');
    }

    public function testSubmissionManualLoggedIn()
    {
        $category = Category::all()->where('name', 'Pěšák')->first();
        $user = User::factory()->create();
        array_push($this->usersToDelete, $user->id);

        $this->get('/');
        Auth::login($user);

        $this->get('/submission');
        $doc = new Document($this->response->getContent(), false);
        $csrfToken = $doc->first('form#submission-form input[name=_csrf]')->value;

        $this->post('/submission', [
            '_csrf' => $csrfToken,
            'step' => 2,
            'contest' => $this->contest->name,
            'category' => $category->name,
            'diaryUrl' => 'https://example.com/diary/url',
            'callSign' => 'Test call sign',
            'qthName' => 'Test QTH name',
            'qthLocator' => 'jn79so',
            'qsoCount' => '99',
            'email' => 'name@example.com'
        ]);
        $this->seeInDatabase('diary', [
            'contest_id' => $this->contest->id,
            'category_id' => $category->id,
            'user_id' => $user->id,
            'diary_url' => 'https://example.com/diary/url',
            'call_sign' => 'Test call sign',
            'qth_name' => 'Test QTH name',
            'qth_locator' => 'JN79SO',
            'qth_locator_lon' => '15.541667',
            'qth_locator_lat' => '49.604167',
            'qso_count' => '99',
            'email' => 'name@example.com'
        ]);
        $this->get('/submission?step=2');
        $this->response->assertSeeText('Hlášení do soutěže ' . $this->contest->name . ' bylo úspěšně zpracováno.');
    }

    public function testSubmissionManualDiaryUrlDuplicity()
    {
        $category = Category::all()->where('name', 'Pěšák')->first();

        for ($i = 0; $i <= 1; $i++) {
            $this->get('/submission');
            $doc = new Document($this->response->getContent(), false);
            $csrfToken = $doc->first('form#submission-form input[name=_csrf]')->value;

            $this->post('/submission', [
                '_csrf' => $csrfToken,
                'step' => 2,
                'contest' => $this->contest->name,
                'category' => $category->name,
                'diaryUrl' => 'https://example.com/diary/url',
                'callSign' => 'Test call sign',
                'qthName' => 'Test QTH name',
                'qthLocator' => 'JN79SO',
                'qsoCount' => '99',
                'email' => 'name@example.com'
            ]);
        }
        $this->get('/submission?step=2');
        $this->response->assertSeeText('Pole diary url již obsahuje v databázi stejný záznam.');
    }

    public function testSubmissionManualTwoSubmissionsWithoutDiaryUrl()
    {
        $category = Category::all()->where('name', 'Pěšák')->first();

        for ($i = 0; $i <= 1; $i++) {
            $this->get('/submission');
            $doc = new Document($this->response->getContent(), false);
            $csrfToken = $doc->first('form#submission-form input[name=_csrf]')->value;

            $this->post('/submission', [
                '_csrf' => $csrfToken,
                'step' => 2,
                'contest' => $this->contest->name,
                'category' => $category->name,
                'diaryUrl' => '',
                'callSign' => 'Test call sign without diary URL ' . ($i + 1),
                'qthName' => 'Test QTH name without diary URL ' . ($i + 1),
                'qthLocator' => 'JN79SO',
                'qsoCount' => 99 - $i,
                'email' => 'name@example.com'
            ]);
        }

        $this->get('/results');
        $this->response->assertSeeTextInOrder([
            $this->contest->name,
            ' (průběžné pořadí)',
            '1.',
            'Test call sign without diary URL 1',
            'Test QTH name without diary URL 1',
            'JN79SO',
            '99',
            '2.',
            'Test call sign without diary URL 2',
            'Test QTH name without diary URL 2',
            'JN79SO',
            '98',
        ]);
    }

    public function testSubmissionEmptyForm()
    {
        $category = Category::all()->where('name', 'Pěšák')->first();

        $this->post('/submission', [
            '_csrf' => $this->csrfToken,
            'step' => 2
        ]);
        $this->get('/submission?step=2');
        $this->response->assertSeeText('Pole contest je vyžadováno.');
        $this->response->assertSeeText('Pole category je vyžadováno.');
        $this->response->assertSeeText('Pole call sign je vyžadováno.');
        $this->response->assertSeeText('Pole qth name je vyžadováno.');
        $this->response->assertSeeText('Pole qth locator je vyžadováno.');
        $this->response->assertSeeText('Pole qso count je vyžadováno.');
        $this->response->assertSeeText('Pole email je vyžadováno.');
    }

    public function testDiarySourceError()
    {
        $originalUsername = config('ctvero.cbpmrInfoApiAuthUsername');
        $originalPassword = config('ctvero.cbpmrInfoApiAuthPassword');
        config([
            'ctvero.cbpmrInfoApiAuthUsername' => 'test-user-test-user-test-user',
            'ctvero.cbpmrInfoApiAuthPassword' => 'test-pass-test-pass-test-pass' ]);

        $this->post('/submission', [
            '_csrf' => $this->csrfToken,
            'step' => 1,
            'diaryUrl' => 'https://www.cbpmr.info/share/4902b6' ]);
        $this->seeStatusCode(500);
        $this->response->assertSeeText('Deník se nepodařilo načíst.');

        config([
            'ctvero.cbpmrInfoApiAuthUsername' => $originalUsername,
            'ctvero.cbpmrInfoApiAuthPassword' => $originalPassword ]);
    }

    public function testDiarySourceCbdxCz()
    {
        $this->post('/submission', [
            '_csrf' => $this->csrfToken,
            'step' => 1,
            'diaryUrl' => 'https://drive.cbdx.cz/xdenik1503.ht0m' ]);
        $diaryInSession = $this->app['session.store']->all()['diary'];
        $this->assertEquals($diaryInSession['url'], 'https://drive.cbdx.cz/xdenik1503.ht0m');
        $this->assertEquals($diaryInSession['callSign'], 'Expedice Morava');
        $this->assertEquals($diaryInSession['qthName'], 'Bystré');
        $this->assertEquals($diaryInSession['qthLocator'], 'JN99FO');
        $this->assertEquals($diaryInSession['qsoCount'], 27);
        $this->get('/submission?step=2');
        $this->response->assertSeeText('Krok 2: Kontrola a doplnění hlášení');
    }

    public function testDiarySourceCbdxCzPlainHttp()
    {
        $this->post('/submission', [
            '_csrf' => $this->csrfToken,
            'step' => 1,
            'diaryUrl' => 'http://drive.cbdx.cz/xdenik1503.ht0m' ]);
        $diaryInSession = $this->app['session.store']->all()['diary'];
        $this->assertEquals($diaryInSession['url'], 'http://drive.cbdx.cz/xdenik1503.ht0m');
        $this->assertEquals($diaryInSession['callSign'], 'Expedice Morava');
        $this->assertEquals($diaryInSession['qthName'], 'Bystré');
        $this->assertEquals($diaryInSession['qthLocator'], 'JN99FO');
        $this->assertEquals($diaryInSession['qsoCount'], 27);
        $this->get('/submission?step=2');
        $this->response->assertSeeText('Krok 2: Kontrola a doplnění hlášení');
    }

    public function testDiarySourceCbpmrCz()
    {
        $this->post('/submission', [
            '_csrf' => $this->csrfToken,
            'step' => 1,
            'diaryUrl' => 'https://www.cbpmr.cz/deniky/19124.htm' ]);
        $diaryInSession = $this->app['session.store']->all()['diary'];
        $this->assertEquals($diaryInSession['url'], 'https://www.cbpmr.cz/deniky/19124.htm');
        $this->assertEquals($diaryInSession['callSign'], 'Pepa Klobouky');
        $this->assertEquals($diaryInSession['qthName'], 'Malá Fatra Minčol');
        $this->assertEquals($diaryInSession['qthLocator'], 'JN99JC');
        $this->assertEquals($diaryInSession['qsoCount'], 1);
        $this->get('/submission?step=2');
        $this->response->assertSeeText('Krok 2: Kontrola a doplnění hlášení');
    }

    public function testDiarySourceCbpmrCzPlainHttp()
    {
        $this->post('/submission', [
            '_csrf' => $this->csrfToken,
            'step' => 1,
            'diaryUrl' => 'http://www.cbpmr.cz/deniky/19124.htm' ]);
        $diaryInSession = $this->app['session.store']->all()['diary'];
        $this->assertEquals($diaryInSession['url'], 'http://www.cbpmr.cz/deniky/19124.htm');
        $this->assertEquals($diaryInSession['callSign'], 'Pepa Klobouky');
        $this->assertEquals($diaryInSession['qthName'], 'Malá Fatra Minčol');
        $this->assertEquals($diaryInSession['qthLocator'], 'JN99JC');
        $this->assertEquals($diaryInSession['qsoCount'], 1);
        $this->get('/submission?step=2');
        $this->response->assertSeeText('Krok 2: Kontrola a doplnění hlášení');
    }

    public function testDiarySourceCbpmrInfo()
    {
        if (! config('ctvero.cbpmrInfoApiUrl')) {
            $this->markTestSkipped('Access to cbpmr.info API is not configured.');
        }
        $this->post('/submission', [
            '_csrf' => $this->csrfToken,
            'step' => 1,
            'diaryUrl' => 'https://www.cbpmr.info/share/4902b6' ]);
        $diaryInSession = $this->app['session.store']->all()['diary'];
        $this->assertEquals($diaryInSession['url'], 'https://www.cbpmr.info/share/4902b6');
        $this->assertEquals($diaryInSession['callSign'], 'Filip 84');
        $this->assertEquals($diaryInSession['qthName'], 'Olomouc');
        $this->assertEquals($diaryInSession['qthLocator'], 'JN89PO');
        $this->assertTrue($diaryInSession['qsoCount'] > 0);
        $this->get('/submission?step=2');
        $this->response->assertSeeText('Krok 2: Kontrola a doplnění hlášení');
    }

    public function testDiarySourceCbpmrInfoPlainHttp()
    {
        if (! config('ctvero.cbpmrInfoApiUrl')) {
            $this->markTestSkipped('Access to cbpmr.info API is not configured.');
        }
        $this->post('/submission', [
            '_csrf' => $this->csrfToken,
            'step' => 1,
            'diaryUrl' => 'http://www.cbpmr.info/share/4902b6' ]);
        $diaryInSession = $this->app['session.store']->all()['diary'];
        $this->assertEquals($diaryInSession['url'], 'https://www.cbpmr.info/share/4902b6');
        $this->assertEquals($diaryInSession['callSign'], 'Filip 84');
        $this->assertEquals($diaryInSession['qthName'], 'Olomouc');
        $this->assertEquals($diaryInSession['qthLocator'], 'JN89PO');
        $this->assertTrue($diaryInSession['qsoCount'] > 0);
        $this->get('/submission?step=2');
        $this->response->assertSeeText('Krok 2: Kontrola a doplnění hlášení');
    }

    public function testDiaryAlreadyInDb()
    {
        $this->post('/submission', [
            '_csrf' => $this->csrfToken,
            'step' => 1,
            'diaryUrl' => 'http://www.cbpmr.cz/deniky/24597.htm' ]);
        $this->get('/submission?step=2');
        $this->response->assertSeeText('Pole diary url již obsahuje v databázi stejný záznam.');
    }

    public function testSubmissionFormMissingCsrf()
    {
        $this->post('/submission', []);
        $this->seeStatusCode(403);
    }

    public function testSubmissionFormMissingAllData()
    {
        $this->post('/submission', [
            '_csrf' => $this->csrfToken,
        ]);
        $this->seeStatusCode(400);
        $this->response->assertSeeText('Neplatný formulářový krok nebo neúplný požadavek');
    }

    public function testSubmissionMissingDiaryUrl()
    {
        $this->post('/submission', [
            '_csrf' => $this->csrfToken,
            'step' => 1
        ]);
        $this->seeStatusCode(400);
        $this->response->assertSeeText('Neúplný požadavek');
    }

    public function testSubmissionUnknownDiarySource()
    {
        $this->post('/submission', [
            '_csrf' => $this->csrfToken,
            'step' => 1,
            'diaryUrl' => 'http://example.com/unknown/diary/url' ]);
        $this->seeStatusCode(422);
        $this->response->assertSeeText('Neznámý zdroj deníku');
    }
}
