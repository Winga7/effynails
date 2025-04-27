<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Les données du formulaire de contact.
     *
     * @var array
     */
    public $formData;

    /**
     * Créer une nouvelle instance de message.
     */
    public function __construct(array $formData)
    {
        $this->formData = $formData;
    }

    /**
     * Obtenir l'enveloppe du message.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouveau message depuis le formulaire de contact - ' . config('app.name'),
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            replyTo: [
                new Address($this->formData['email'], $this->formData['name']),
            ],
        );
    }

    /**
     * Obtenir le contenu du message.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
        );
    }

    /**
     * Obtenir les pièces jointes du message.
     *
     * @return array<\Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
} 