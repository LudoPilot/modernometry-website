<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return inertia('Contact/Index');
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'nullable|string|max:100',
            'lastname'  => 'nullable|string|max:100',
            'email'     => 'required|email',
            'message'   => 'required|string|min:10',
        ]);


        if (empty($validated['firstname']) && empty($validated['lastname'])) {
            return back()->withErrors([
                'firstname' => 'Veuillez entrer au moins votre nom ou votre prénom.',
            ]);
        }

        // Envoi d'email (exemple simple)
        Mail::raw(
            "Message envoyé par : {$validated['firstname']} {$validated['lastname']}\nEmail : {$validated['email']}\n\n{$validated['message']}",
            fn ($mail) => $mail
                ->to('contact@modernometry.com')
                ->subject('Nouveau message depuis le formulaire de contact')
        );

        return back()->with('success', 'Votre message a bien été envoyé !');
    }
}
