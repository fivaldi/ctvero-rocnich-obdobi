<?php

use HtmlValidator\Validator;

class HtmlTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->errors = array();
        $this->validator = new Validator();
        $this->validator->setParser(Validator::PARSER_HTML5);
    }

    public function commonErrors($requestUri)
    {
        $this->get($requestUri);
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

    public function test404()
    {
        $this->commonErrors('/non-existing-page');
        $this->assertEquals($this->errors, []);
    }
}
