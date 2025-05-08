<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Afficher la page de contact.
     */
    public function index()
    {
        return Inertia::render('Contact');
    }

    /**
     * Traiter l'envoi du formulaire de contact.
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            // Log détaillé avant l'envoi
            Log::info('Configuration de l\'email avant envoi', [
                'to' => config('mail.to_address'),
                'from' => config('mail.from.address'),
                'mailer' => config('mail.default'),
                'firstName' => $validated['firstName'],
                'lastName' => $validated['lastName'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'subject' => $validated['subject'],
            ]);

            // Solution de contournement - écrire directement dans les logs
            Log::info('CONTENU DU MESSAGE DE CONTACT', [
                'nom' => $validated['firstName'] . ' ' . $validated['lastName'],
                'email' => $validated['email'],
                'telephone' => $validated['phone'],
                'sujet' => $validated['subject'],
                'message' => $validated['message'],
                'date' => now()->format('d/m/Y H:i:s')
            ]);

            // Envoi de l'email
            Mail::to(config('mail.to_address'))->send(new ContactFormMail($validated));

            // Log de succès
            Log::info('Email envoyé avec succès');

            // Redirection avec message de succès
            return redirect()->route('contact')->with('success', 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.');
        } catch (\Exception $e) {
            // Log détaillé de l'erreur
            Log::error('Erreur lors de l\'envoi du mail de contact', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            // Redirection avec message d'erreur
            return redirect()->route('contact')->with('error', 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer ultérieurement. Erreur: ' . $e->getMessage());
        }
    }
}
