<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class TestMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Teste l\'envoi d\'un email avec la configuration actuelle';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Envoi d\'un email de test...');
        
        $testData = [
            'name' => 'Test Utilisateur',
            'email' => 'test@example.com',
            'message' => 'Ceci est un message de test envoyé depuis la commande Artisan mail:test.',
        ];
        
        try {
            $this->info('Tentative d\'envoi à: ' . config('mail.to_address'));
            $this->info('Depuis: ' . config('mail.from.address'));
            
            Mail::to(config('mail.to_address'))->send(new ContactFormMail($testData));
            
            $this->info('Email envoyé avec succès!');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Erreur lors de l\'envoi de l\'email: ' . $e->getMessage());
            $this->error('Trace: ' . $e->getTraceAsString());
            return Command::FAILURE;
        }
    }
} 