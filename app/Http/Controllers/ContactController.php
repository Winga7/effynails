<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

/**
 * 📞 ContactController
 *
 * Gère le formulaire de contact et l'envoi des messages
 * Assure la validation des données et la journalisation des messages
 * Gère les erreurs et les confirmations pour l'utilisateur
 */
class ContactController extends Controller
{
    /**
     * 📄 Afficher la page de contact
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Contact');
    }

    /**
     * 📨 Traiter l'envoi du formulaire de contact
     *
     * Valide les données du formulaire, envoie l'email et journalise l'activité
     * En cas d'erreur, capture l'exception et informe l'utilisateur
     *
     * @param Request $request Les données du formulaire
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(Request $request)
    {
        // ✅ Validation des données avec messages d'erreur personnalisés
        $validated = $request->validate([
            'firstName' => 'required|string|min:2|max:255',
            'lastName' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|min:3|max:255',
            'message' => 'required|string|min:10',
        ], [
            'firstName.required' => 'Le prénom est obligatoire.',
            'firstName.min' => 'Le prénom doit avoir au moins 2 caractères.',
            'lastName.required' => 'Le nom est obligatoire.',
            'lastName.min' => 'Le nom doit avoir au moins 2 caractères.',
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email' => 'Veuillez saisir une adresse e-mail valide.',
            'phone.required' => 'Le numéro de téléphone est obligatoire.',
            'subject.required' => 'Le sujet est obligatoire.',
            'subject.min' => 'Le sujet doit avoir au moins 3 caractères.',
            'message.required' => 'Le message est obligatoire.',
            'message.min' => 'Le message doit avoir au moins 10 caractères.',
        ]);

        try {
            // 📝 Journalisation détaillée avant l'envoi
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

            // 📋 Solution de contournement - écriture directe dans les logs
            Log::info('CONTENU DU MESSAGE DE CONTACT', [
                'nom' => $validated['firstName'] . ' ' . $validated['lastName'],
                'email' => $validated['email'],
                'telephone' => $validated['phone'],
                'sujet' => $validated['subject'],
                'message' => $validated['message'],
                'date' => now()->format('d/m/Y H:i:s')
            ]);

            // 📧 Envoi de l'email via le système de mail Laravel
            Mail::to(config('mail.to_address'))->send(new ContactFormMail($validated));

            // ✅ Journalisation du succès
            Log::info('Email envoyé avec succès');

            // 🔙 Redirection avec message de succès
            return redirect()->route('contact')->with('success', 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.');
        } catch (\Exception $e) {
            // ❌ Journalisation détaillée de l'erreur
            Log::error('Erreur lors de l\'envoi du mail de contact', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            // 🔙 Redirection avec message d'erreur
            return redirect()->route('contact')->with('error', 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer ultérieurement. Erreur: ' . $e->getMessage());
        }
    }
}
