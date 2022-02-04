<?php

use DiDom\Document;

class MessageTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->get('/lang/cs');
        $this->get('/');

        // CSRF
        $doc = new Document($this->response->getContent(), false);
        $this->csrfToken = $doc->first('form#contact-form input[name=_csrf]')->value;
    }

    public function testMessageFormMissingCsrf()
    {
        $this->post('/message', []);
        $this->seeStatusCode(403);
    }

    public function testMessageFormMissingData()
    {
        $this->post('/message', [ '_csrf' => $this->csrfToken ]);
        $this->get('/');
        $this->response->assertSeeText('Pole email je vyžadováno.');
        $this->response->assertSeeText('Pole subject je vyžadováno.');
        $this->response->assertSeeText('Pole message je vyžadováno.');
    }

    public function testMessageFormWrongMail()
    {
        $this->post('/message', [ '_csrf' => $this->csrfToken, 'email' => 'Wrong mail address' ]);
        $this->get('/');
        $this->response->assertSeeText('Pole email obsahuje neplatnou e-mailovou adresu.');
    }
}
