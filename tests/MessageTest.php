<?php

class MessageTest extends TestCase
{
    public function testMessageFormMissingData()
    {
        $this->post('/message', []);
        $this->get('/');
        $this->response->assertSeeText('Pole email je vyžadováno.');
        $this->response->assertSeeText('Pole subject je vyžadováno.');
        $this->response->assertSeeText('Pole message je vyžadováno.');
    }

    public function testMessageFormWrongMail()
    {
        $this->post('/message', [ 'email' => 'Wrong mail address' ]);
        $this->get('/');
        $this->response->assertSeeText('Pole email obsahuje neplatnou e-mailovou adresu.');
    }
}
