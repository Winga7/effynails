<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Tarif;
use Google_Client;
use Google_Service_Calendar;
use Carbon\Carbon;

class CalendarSyncService
{
    protected $client;
    protected $service;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setAuthConfig(storage_path('app/google-credentials.json'));
        $this->client->addScope(Google_Service_Calendar::CALENDAR_READONLY);

        $this->service = new Google_Service_Calendar($this->client);
    }

    public function syncAppointments()
    {
        // Récupérer les événements du calendrier pour aujourd'hui
        $startOfDay = Carbon::today()->toRfc3339String();
        $endOfDay = Carbon::today()->endOfDay()->toRfc3339String();

        $events = $this->service->events->listEvents(
            config('services.google.calendar_id'),
            [
                'timeMin' => $startOfDay,
                'timeMax' => $endOfDay,
                'singleEvents' => true,
                'orderBy' => 'startTime',
            ]
        );

        foreach ($events->getItems() as $event) {
            // Vérifier si c'est un événement Cal.com
            if ($this->isCalComEvent($event)) {
                $this->processEvent($event);
            }
        }
    }

    protected function isCalComEvent($event)
    {
        // Vérifier si l'événement provient de Cal.com
        // On peut vérifier l'organisateur ou d'autres métadonnées
        return str_contains($event->getOrganizer()->getEmail(), 'cal.com');
    }

    protected function processEvent($event)
    {
        // Extraire les informations du service depuis les métadonnées de l'événement
        $serviceId = $this->extractServiceId($event);

        if ($serviceId) {
            $tarif = Tarif::where('google_calendar_id', $serviceId)->first();

            if ($tarif) {
                // Créer ou mettre à jour le rendez-vous
                Appointment::updateOrCreate(
                    ['google_calendar_id' => $event->getId()],
                    [
                        'tarif_id' => $tarif->id,
                        'client_name' => $event->getAttendees()[0]->getDisplayName(),
                        'client_email' => $event->getAttendees()[0]->getEmail(),
                        'start_time' => Carbon::parse($event->getStart()->getDateTime()),
                        'end_time' => Carbon::parse($event->getEnd()->getDateTime()),
                        'status' => $this->mapStatus($event->getStatus()),
                        'notes' => $event->getDescription(),
                        'is_new_client' => $this->isNewClient($event->getAttendees()[0]->getEmail()),
                    ]
                );
            }
        }
    }

    protected function extractServiceId($event)
    {
        // Extraire l'ID du service depuis les métadonnées de l'événement
        // Cela dépendra de la façon dont Cal.com stocke ces informations
        return $event->getExtendedProperties()->getPrivate()['serviceId'] ?? null;
    }

    protected function mapStatus($googleStatus)
    {
        return match ($googleStatus) {
            'confirmed' => 'confirmed',
            'tentative' => 'pending',
            'cancelled' => 'cancelled',
            default => 'pending',
        };
    }

    protected function isNewClient($email)
    {
        // Vérifier si c'est un nouveau client en regardant l'historique des rendez-vous
        return !Appointment::where('client_email', $email)
            ->where('start_time', '<', Carbon::now())
            ->exists();
    }
}
