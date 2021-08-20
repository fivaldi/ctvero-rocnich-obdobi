<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\MessageMail;

class MessageController extends BaseController
{
    public function send(Request $request)
    {
        $messages = [
            'email' => 'Pole :attribute obsahuje neplatnou e-mailovou adresu.',
            'required' => 'Pole :attribute je vyžadováno.',
            'max' => 'Pole :attribute přesahuje povolenou délku :max znaků.',
        ];
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'subject' => 'required|max:255',
            'spamCheck' => 'required',
            'message' => 'required|max:2000',
        ], $messages);

        if ($validator->fails()) {
            $request->session()->put('mailErrors', $validator->errors()->all());
            return redirect('/#tm-section-6');
        }

        if (trim($request->input('spamCheck')) != 2) {
            $request->session()->put('mailErrors', array('Kontrola proti spamu není úspěšná.'));
            return redirect('/#tm-section-6');
        }

        try {
            $msg = new MessageMail($request->input('email'),
                                   $request->input('subject'),
                                   $request->input('message'));
            Mail::to(config('ctvero.ownerMail'))->send($msg);
            $request->session()->put('mailSuccess', 'Zpráva byla úspěšně odeslána.');
        } catch (\Exception $e) {
            $request->session()->put('mailErrors', array('Odeslání zprávy se nezdařilo.'));
        }
        return redirect('/#tm-section-6');
    }
}
