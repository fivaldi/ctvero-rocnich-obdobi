<?php

use HtmlValidator\Validator;

use App\Models\User;

class HtmlTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->errors = array();
        $this->validator = new Validator();
        $this->validator->setParser(Validator::PARSER_HTML5);
        $this->usersToDelete = array();
    }

    public function tearDown(): void
    {
        User::whereIn('id', $this->usersToDelete)->delete();

        parent::tearDown();
    }

    public function commonErrors($requestUri = NULL)
    {
        if ($requestUri) {
            $this->get($requestUri);
        }
        $result = $this->validator->validateDocument($this->response->getContent());
        foreach ($result->getErrors() as $error) {
            if ($error->getText() !== 'Element “img” is missing required attribute “src”.') {
                $this->errors[] = $error->getText();
            }
        }
        $this->assertEquals($result->hasWarnings(), false);
    }

    public function testIndex()
    {
        $this->commonErrors('/');
        $this->assertEquals($this->errors, []);
    }

    public function testContests()
    {
        $this->commonErrors('/contests');
        $this->assertEquals($this->errors, []);
    }

    public function testContest()
    {
        $this->commonErrors('/contest/Předkolo-Zima-2020');
        $this->assertEquals($this->errors, []);
    }

    public function testProfile()
    {
        $user = User::factory()->create();
        array_push($this->usersToDelete, $user->id);

        $this->get('/');
        Auth::login($user);
        $this->get('/profile');
        $this->commonErrors();
        $this->assertEquals($this->errors, []);
    }

    public function testResults()
    {
        $this->commonErrors('/results');
        $this->assertEquals($this->errors, []);
    }

    public function testSubmissionForm()
    {
        $this->commonErrors('/submission');
        $this->assertEquals($this->errors, []);
    }

    public function testSubmissionFormStepTwo()
    {
        $this->commonErrors('/submission?step=2');
        $this->assertEquals($this->errors, []);
    }

    public function testTermsAndPrivacy()
    {
        $this->commonErrors('/terms-and-privacy');
        $this->assertEquals($this->errors, []);
    }

    public function test404()
    {
        $this->commonErrors('/non-existing-page');
        $this->assertEquals($this->errors, []);
    }
}
