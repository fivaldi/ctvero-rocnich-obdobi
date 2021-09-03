<?php

use App\Models\Contest;
use App\Models\Category;

class SubmissionTest extends TestCase
{
    public function setUp(): void {
        parent::setUp();

        $this->contest = Contest::factory()->create();
        $this->seeInDatabase('contest', [ 'name' => $this->contest->name ]);
    }

    public function testSubmissionManual()
    {
        $category = Category::all()->where('name', 'Pěšák')->first();

        $this->post('/submission', [
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
        $this->get('/hlaseni?krok-2');
        $this->seeInDatabase('diary', [
            'contest_id' => $this->contest->id,
            'category_id' => $category->id,
            'diary_url' => 'https://example.com/diary/url',
            'call_sign' => 'Test call sign',
            'qth_name' => 'Test QTH name',
            'qth_locator' => 'JN79SO',
            'qso_count' => '99',
            'email' => 'name@example.com'
        ]);
        $this->response->assertSeeText('Hlášení do soutěže bylo úspěšně zpracováno.');
    }

    public function testSubmissionEmptyForm()
    {
        $category = Category::all()->where('name', 'Pěšák')->first();

        $this->post('/submission', [ 'step' => 2 ]);
        $this->get('/hlaseni?krok-2');
        $this->response->assertSeeText('Pole contest je vyžadováno.');
        $this->response->assertSeeText('Pole category je vyžadováno.');
        $this->response->assertSeeText('Pole diary url je vyžadováno.');
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
            'step' => 1,
            'diaryUrl' => 'https://drive.cbdx.cz/xdenik1503.ht0m' ]);
        $diaryInSession = $this->app['session.store']->all()['diary'];
        $this->assertEquals($diaryInSession['url'], 'https://drive.cbdx.cz/xdenik1503.ht0m');
        $this->assertEquals($diaryInSession['callSign'], 'Expedice Morava');
        $this->assertEquals($diaryInSession['qthName'], 'Bystré');
        $this->assertEquals($diaryInSession['qthLocator'], 'JN99FO');
        $this->assertEquals($diaryInSession['qsoCount'], '27');
        $this->get('/hlaseni?krok=2');
        $this->response->assertSeeText('Krok 2: Kontrola a doplnění hlášení');
    }

    public function testDiarySourceCbpmrCz()
    {
        $this->post('/submission', [
            'step' => 1,
            'diaryUrl' => 'http://www.cbpmr.cz/deniky/19124.htm' ]);
        $diaryInSession = $this->app['session.store']->all()['diary'];
        $this->assertEquals($diaryInSession['url'], 'http://www.cbpmr.cz/deniky/19124.htm');
        $this->assertEquals($diaryInSession['callSign'], 'Pepa Klobouky');
        $this->assertEquals($diaryInSession['qthName'], 'Malá Fatra Minčol');
        $this->assertEquals($diaryInSession['qthLocator'], 'JN99JC');
        $this->assertEquals($diaryInSession['qsoCount'], '1');
        $this->get('/hlaseni?krok=2');
        $this->response->assertSeeText('Krok 2: Kontrola a doplnění hlášení');
    }

    public function testDiarySourceCbpmrInfo()
    {
        if (! config('ctvero.cbpmrInfoApiUrl')) {
            $this->markTestSkipped('Access to cbpmr.info API is not configured.');
        }
        $this->post('/submission', [
            'step' => 1,
            'diaryUrl' => 'https://www.cbpmr.info/share/4902b6' ]);
        $diaryInSession = $this->app['session.store']->all()['diary'];
        $this->assertEquals($diaryInSession['url'], 'https://www.cbpmr.info/share/4902b6');
        $this->assertEquals($diaryInSession['callSign'], 'Filip 84');
        $this->assertEquals($diaryInSession['qthName'], 'Olomouc');
        $this->assertEquals($diaryInSession['qthLocator'], 'JN89PO');
        $this->assertEquals($diaryInSession['qsoCount'], '36');
        $this->get('/hlaseni?krok=2');
        $this->response->assertSeeText('Krok 2: Kontrola a doplnění hlášení');
    }

    public function testDiaryAlreadyInDb()
    {
        $this->post('/submission', [
            'step' => 1,
            'diaryUrl' => 'http://www.cbpmr.cz/deniky/24597.htm' ]);
        $this->get('/hlaseni?krok=2');
        $this->response->assertSeeText('Pole diary url již obsahuje v databázi stejný záznam.');
    }

    public function testSubmissionFormMissingAllData()
    {
        $this->post('/submission', []);
        $this->seeStatusCode(400);
        $this->response->assertSeeText('Neplatný formulářový krok nebo neúplný požadavek');
    }

    public function testSubmissionMissingDiaryUrl()
    {
        $this->post('/submission', [ 'step' => 1 ]);
        $this->seeStatusCode(400);
        $this->response->assertSeeText('Neúplný požadavek');
    }

    public function testSubmissionUnknownDiarySource()
    {
        $this->post('/submission', [
            'step' => 1,
            'diaryUrl' => 'http://example.com/unknown/diary/url' ]);
        $this->seeStatusCode(422);
        $this->response->assertSeeText('Neznámý zdroj deníku');
    }
}
