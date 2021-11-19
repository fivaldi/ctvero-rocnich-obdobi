<?php

namespace App\Exceptions;

use Exception;
use App\Http\Controllers\IndexController;

class MessageException extends Exception
{
    public function __construct($statusCode, $messages = [])
    {
        $this->statusCode = $statusCode;
        $this->messages = $messages;
    }

    public function render($request)
    {
        $request->session()->flash('messageErrors', $this->messages);
        $indexController = new IndexController;
        return response($indexController->show($request))
                                        ->setStatusCode($this->statusCode);
    }
}
