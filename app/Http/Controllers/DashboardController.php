<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Google_Client;
use Google_Service_Calendar;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

/**
 * 📊 DashboardController
 *
 * Gère l'affichage et la logique du tableau de bord
 * Intègre les données du calendrier Google et les statistiques
 * Fournit les informations sur les rendez-vous à venir
 */
class DashboardController extends Controller
{
    protected $calendarService;

    /**
     * 🔧 Constructeur
     *
     * Initialise la connexion avec l'API Google Calendar
     * Configure les credentials et les scopes nécessaires
     */
    public function __construct()
    {
        $client = new Google_Client();
        $client->setAuthConfig(storage_path('app/google-credentials.json'));
        $client->addScope(Google_Service_Calendar::CALENDAR_READONLY);
        $this->calendarService = new Google_Service_Calendar($client);
    }

    /**
     * 📱 Page principale du tableau de bord
     *
     * Récupère et affiche :
     * - Les rendez-vous du mois
     * - Les statistiques (RDV aujourd'hui, semaine, revenus)
     * - Les prochains rendez-vous
     * - Les services populaires
     *
     * @return \Inertia\Response|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        Log::info('DashboardController index appelé');
        // 📅 Récupération des événements du mois de mai 2025 pour les tests
        $start = Carbon::create(2025, 5, 1, 0, 0, 0);
        $end = Carbon::create(2025, 5, 31, 23, 59, 59);

        $eventsMay = $this->getEvents($start, $end);
        Log::info('Events mai 2025', ['events' => $eventsMay]);

        // 🔍 Filtrage des événements pour ne garder que ceux d'Effynails
        $filteredEvents = collect($eventsMay)->filter(function ($event) {
            return stripos($event->getSummary(), 'effynails') !== false;
        });

        // 📅 Prochains rendez-vous (10 à venir, triés par date)
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
                // 📝 Extraction des informations du rendez-vous
                $summary = $event->getSummary();
                $description = $event->getDescription() ?? '';
                $typeRdv = $summary;
                $clientRdv = '';
                $emailClient = null;
                $telephoneClient = null;
                $messageClient = null;
                $annulationUrl = null;

                // 🔍 Parsing des informations du rendez-vous
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

        // 📊 Calcul des statistiques pour la période affichée
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

        // 📅 Prochain rendez-vous unique (pour le cadre principal)
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

        // 📦 Préparation des données pour la vue
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

        // 🔄 Retourne JSON pour les requêtes API, sinon vue Inertia
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json($data);
        }

        return Inertia::render('Dashboard', $data);
    }

    /**
     * 📅 Récupère les événements du calendrier
     *
     * @param Carbon $start Date de début
     * @param Carbon $end Date de fin
     * @return array Liste des événements
     */
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

    /**
     * 📋 Récupère les derniers rendez-vous
     *
     * @param array|null $events Liste optionnelle d'événements
     * @return \Illuminate\Support\Collection Collection des derniers rendez-vous
     */
    protected function getDerniersRendezVous($events = null)
    {
        // 📥 Récupération des événements si non fournis
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

        // 🔍 Filtrage et transformation des événements
        return collect($events)
            ->filter(function ($event) {
                return stripos($event->getSummary(), 'effynails') !== false;
            })
            ->sortByDesc(function ($event) {
                return $event->getStart() && $event->getStart()->getDateTime() ? strtotime($event->getStart()->getDateTime()) : 0;
            })
            ->take(5)
            ->map(function ($event) {
                // 📝 Extraction des informations du rendez-vous
                $summary = $event->getSummary();
                $description = $event->getDescription() ?? '';
                $typeRdv = $summary;
                $clientRdv = '';
                $emailClient = null;
                $telephoneClient = null;
                $messageClient = null;

                // 🔍 Parsing des informations
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
