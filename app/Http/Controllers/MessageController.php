<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\MessageMail;
use App\Http\Utilities;
use App\Exceptions\MessageException;

class MessageController extends BaseController
{
    public function send(Request $request)
    {
        Utilities::validateCsrfToken();
        Utilities::checkRecaptcha();

        $messages = [
            'email' => __('Pole :attribute obsahuje neplatnou e-mailovou adresu.'),
            'required' => __('Pole :attribute je vyžadováno.'),
            'max' => __('Pole :attribute přesahuje povolenou délku :max znaků.'),
        ];
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'subject' => 'required|max:255',
            'message' => 'required|max:2000',
        ], $messages);

        if ($validator->fails()) {
            Session::flash('messageErrors', $validator->errors()->all());
            return redirect(route('index'));
        }

        try {
            $msg = new MessageMail($request->input('email'),
                                   $request->input('subject'),
                                   $request->input('message'));
            Mail::to(config('ctvero.ownerMail'))->send($msg);
            Session::flash('messageSuccess', __('Zpráva byla úspěšně odeslána.'));
            return redirect(route('index'));
        } catch (\Exception $e) {
            throw new MessageException(500, array(__('Odeslání zprávy se nezdařilo.')));
        }
    }
}
