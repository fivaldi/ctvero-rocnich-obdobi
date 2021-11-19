<?php

namespace App\Exceptions;

use Exception;
use App\Http\Controllers\ContestController;

class ContestException extends Exception
{
    public function __construct($statusCode, $messages = [])
    {
        $this->statusCode = $statusCode;
        $this->messages = $messages;
    }

    public function render($request)
    {
        $request->session()->flash('errors', $this->messages);
        return response((new ContestController)->showAll($request))->setStatusCode($this->statusCode);
    }
}
