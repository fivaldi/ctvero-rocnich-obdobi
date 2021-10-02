<?php

namespace App\Exceptions;

use Exception;
use App\Http\Controllers\SubmissionController;

class SubmissionException extends Exception
{
    public function __construct($statusCode, $messages = [], $resetStep = false) {
        $this->statusCode = $statusCode;
        $this->messages = $messages;
        $this->resetStep = $resetStep;
    }
    public function render($request)
    {
        $request->session()->flash('submissionErrors', $this->messages);
        return response((new SubmissionController)->show($request, $this->resetStep))
                                                  ->setStatusCode($this->statusCode);
    }
}
