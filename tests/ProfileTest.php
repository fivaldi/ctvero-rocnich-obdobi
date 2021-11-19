<?php

use Illuminate\Support\Facades\Auth;

use App\Models\User;

class ProfileTest extends TestCase
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

    public function testProfileNotLoggedIn()
    {
        $this->get('/profile');
        $this->seeStatusCode(401);
        $this->response->assertSeeText('Je vyžadováno přihlášení.');
    }

    public function testProfile()
    {
        $user = User::factory()->create();
        array_push($this->usersToDelete, $user->id);

        $this->get('/');
        Auth::login($user);
        $this->get('/profile');
        $this->seeStatusCode(200);
        $this->response->assertSeeInOrder([
            'name',
            $user->name,
            'email',
            $user->email,
        ]);
    }
}
