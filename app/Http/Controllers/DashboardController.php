<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Google_Client;
use Google_Service_Calendar;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    protected $calendarService;

    public function __construct()
    {
        $client = new Google_Client();
        $client->setAuthConfig(storage_path('app/google-credentials.json'));
        $client->addScope(Google_Service_Calendar::CALENDAR_READONLY);
        $this->calendarService = new Google_Service_Calendar($client);
    }

    public function index()
    {
        Log::info('DashboardController index appelé');
        // Afficher tous les événements du mois de mai 2025 pour le test
        $start = Carbon::create(2025, 5, 1, 0, 0, 0);
        $end = Carbon::create(2025, 5, 31, 23, 59, 59);

        $eventsMay = $this->getEvents($start, $end);
        Log::info('Events mai 2025', ['events' => $eventsMay]);

        // Filtrer les événements du mois pour ne garder que ceux qui concernent Effynails
        $filteredEvents = collect($eventsMay)->filter(function ($event) {
            return stripos($event->getSummary(), 'effynails') !== false;
        });

        // Prochains rendez-vous (10 à venir, date >= maintenant, triés par date croissante)
        $now = Carbon::now();
        $prochainsRendezVous = $filteredEvents
            ->filter(function ($event) use ($now) {
                $date = $event->getStart() && $event->getStart()->getDateTime() ? Carbon::parse($event->getStart()->getDateTime()) : null;
                return $date && $date->greaterThanOrEqualTo($now);
            })
            ->sortBy(function ($event) {
                return $event->getStart() && $event->getStart()->getDateTime() ? strtotime($event->getStart()->getDateTime()) : PHP_INT_MAX;
            })
            ->take(10)
            ->map(function ($event) {
                $summary = $event->getSummary();
                $description = $event->getDescription() ?? '';
                $typeRdv = $summary;
                $clientRdv = '';
                $emailClient = null;
                $telephoneClient = null;
                $messageClient = null;
                $annulationUrl = null;
                if (preg_match('/^(.*?) entre Effynails et (.*)$/i', $summary, $matches)) {
                    $typeRdv = trim($matches[1]);
                    $clientRdv = trim($matches[2]);
                }
                if (preg_match('/Andy\s*\n([\w.%-]+@[\w.-]+\.[A-Za-z]{2,6})/i', $description, $matchesEmail)) {
                    $emailClient = trim($matchesEmail[1]);
                }
                if (preg_match('/Votre numéro de téléphone:\s*([+0-9\s]+)/i', $description, $matchesTel)) {
                    $telephoneClient = trim($matchesTel[1]);
                }
                if (preg_match('/Notes supplémentaires:\s*(.*?)\n\n/s', $description, $matchesMsg)) {
                    $messageClient = trim($matchesMsg[1]);
                }
                if (preg_match('/Besoin de replanifier ou d\'annuler \?\s*(https?:\/\/\S+)/i', $description, $matchesUrl)) {
                    $annulationUrl = trim($matchesUrl[1]);
                }
                return [
                    'id' => $event->getId(),
                    'type' => $typeRdv,
                    'client' => $clientRdv,
                    'date' => $event->getStart() && $event->getStart()->getDateTime() ? Carbon::parse($event->getStart()->getDateTime())->format('d/m/Y H:i') : '',
                    'statut' => $event->getStatus(),
                    'message' => $messageClient,
                    'email' => $emailClient,
                    'telephone' => $telephoneClient,
                    'annulation_url' => $annulationUrl,
                ];
            })
            ->values();

        // Les stats restent sur la période affichée (mai 2025)
        $today = Carbon::today();
        $eventsToday = $filteredEvents->filter(function ($event) use ($today) {
            $date = $event->getStart() && $event->getStart()->getDateTime() ? Carbon::parse($event->getStart()->getDateTime()) : null;
            return $date && $date->isSameDay($today);
        });
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $eventsWeek = $filteredEvents->filter(function ($event) use ($startOfWeek, $endOfWeek) {
            $date = $event->getStart() && $event->getStart()->getDateTime() ? Carbon::parse($event->getStart()->getDateTime()) : null;
            return $date && $date->between($startOfWeek, $endOfWeek);
        });

        // Prochain rendez-vous unique (pour le cadre principal)
        $nextRdv = $prochainsRendezVous->first();
        $prochainRendezVous = $nextRdv ? [
            'type' => $nextRdv['type'],
            'client' => $nextRdv['client'],
            'date' => $nextRdv['date'],
            'message' => $nextRdv['message'],
            'email' => $nextRdv['email'],
            'telephone' => $nextRdv['telephone'],
            'annulation_url' => $nextRdv['annulation_url'],
        ] : null;

        $data = [
            'stats' => [
                'rendezVousAujourdhui' => $eventsToday->count(),
                'rendezVousSemaine' => $eventsWeek->count(),
                'revenusMois' => 0,
            ],
            'prochainRendezVous' => $prochainRendezVous,
            'prochainsRendezVous' => $prochainsRendezVous,
            'servicesPopulaires' => [],
        ];

        // Si c'est une requête API, retourne du JSON
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json($data);
        }

        // Sinon, vue Inertia classique
        return Inertia::render('Dashboard', $data);
    }

    protected function getEvents($start, $end)
    {
        $events = $this->calendarService->events->listEvents(
            config('services.google.calendar_id'),
            [
                'timeMin' => $start->toRfc3339String(),
                'timeMax' => $end->toRfc3339String(),
                'singleEvents' => true,
                'orderBy' => 'startTime',
            ]
        );
        return $events->getItems();
    }

    protected function getDerniersRendezVous($events = null)
    {
        // Si on passe une liste d'événements, on l'utilise, sinon on va chercher les 5 derniers
        if ($events === null) {
            $events = $this->calendarService->events->listEvents(
                config('services.google.calendar_id'),
                [
                    'maxResults' => 5,
                    'orderBy' => 'startTime',
                    'singleEvents' => true,
                ]
            )->getItems();
        }
        // On ne garde que les événements dont le résumé contient "effynails"
        return collect($events)
            ->filter(function ($event) {
                return stripos($event->getSummary(), 'effynails') !== false;
            })
            ->sortByDesc(function ($event) {
                return $event->getStart() && $event->getStart()->getDateTime() ? strtotime($event->getStart()->getDateTime()) : 0;
            })
            ->take(5)
            ->map(function ($event) {
                $summary = $event->getSummary();
                $description = $event->getDescription() ?? '';
                $typeRdv = $summary;
                $clientRdv = '';
                $emailClient = null;
                $telephoneClient = null;
                $messageClient = null;
                if (preg_match('/^(.*?) entre Effynails et (.*)$/i', $summary, $matches)) {
                    $typeRdv = trim($matches[1]);
                    $clientRdv = trim($matches[2]);
                }
                if (preg_match('/Andy\s*\n([\w.%-]+@[\w.-]+\.[A-Za-z]{2,6})/i', $description, $matchesEmail)) {
                    $emailClient = trim($matchesEmail[1]);
                }
                if (preg_match('/Votre numéro de téléphone:\s*([+0-9\s]+)/i', $description, $matchesTel)) {
                    $telephoneClient = trim($matchesTel[1]);
                }
                if (preg_match('/Notes supplémentaires:\s*(.*?)\n\n/s', $description, $matchesMsg)) {
                    $messageClient = trim($matchesMsg[1]);
                }
                return [
                    'id' => $event->getId(),
                    'type' => $typeRdv,
                    'client' => $clientRdv,
                    'date' => $event->getStart() && $event->getStart()->getDateTime() ? Carbon::parse($event->getStart()->getDateTime())->format('d/m/Y H:i') : '',
                    'statut' => $event->getStatus(),
                    'message' => $messageClient,
                    'email' => $emailClient,
                    'telephone' => $telephoneClient,
                ];
            })
            ->values();
    }
}
