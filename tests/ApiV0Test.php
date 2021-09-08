<?php

class ApiV0Test extends TestCase
{
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
}
