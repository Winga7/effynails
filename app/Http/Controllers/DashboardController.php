<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Google_Client;
use Google_Service_Calendar;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Tarif;

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
        try {
            // 📅 Récupération des événements de la semaine courante jusqu'à dans 2 mois
            $start = Carbon::now()->startOfWeek(Carbon::MONDAY);
            $end = Carbon::now()->addMonths(2);

            $events = $this->getEvents($start, $end);
            Log::info('Events récupérés', ['count' => count($events)]);

            // 🔍 Filtrage des événements pour ne garder que ceux d'Effynails
            $filteredEvents = collect($events)->filter(function ($event) {
                return stripos($event->getSummary(), 'effynails') !== false;
            });
            Log::info('Events filtrés', ['count' => $filteredEvents->count()]);

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
            $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
            $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);
            $eventsWeek = $filteredEvents->filter(function ($event) use ($startOfWeek, $endOfWeek) {
                $date = $event->getStart() && $event->getStart()->getDateTime() ? Carbon::parse($event->getStart()->getDateTime()) : null;
                return $date && $date->between($startOfWeek, $endOfWeek);
            });

            // 📦 Calcul des revenus du mois
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();
            $eventsMonth = $filteredEvents->filter(function ($event) use ($startOfMonth, $endOfMonth) {
                $date = $event->getStart() && $event->getStart()->getDateTime() ? Carbon::parse($event->getStart()->getDateTime()) : null;
                return $date && $date->between($startOfMonth, $endOfMonth);
            });
            $tarifs = Tarif::all()->keyBy('nom');
            $revenusMois = 0;
            foreach ($eventsMonth as $event) {
                $summary = $event->getSummary();
                $serviceType = null;
                if (preg_match('/^(.*?) entre Effynails et/i', $summary, $matches)) {
                    $serviceType = trim($matches[1]);
                }
                if ($serviceType && isset($tarifs[$serviceType])) {
                    $revenusMois += floatval($tarifs[$serviceType]->prix);
                } else {
                    Log::warning('Service non trouvé dans les tarifs', ['summary' => $summary, 'serviceType' => $serviceType]);
                }
            }
            Log::info('Revenus du mois calculés', ['revenusMois' => $revenusMois]);

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
                    'revenusMois' => $revenusMois,
                ],
                'prochainRendezVous' => $prochainRendezVous,
                'prochainsRendezVous' => $prochainsRendezVous,
                'servicesPopulaires' => [],
            ];
            return request()->wantsJson() || request()->is('api/*') ? response()->json($data) : Inertia::render('Dashboard', $data);
        } catch (\Exception $e) {
            Log::error('Erreur dans DashboardController index', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Erreur lors du chargement du tableau de bord'], 500);
        }
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
        try {
            Log::info('Tentative de récupération des événements Google Calendar', [
                'start' => $start->toRfc3339String(),
                'end' => $end->toRfc3339String(),
                'calendar_id' => config('services.google.calendar_id')
            ]);

            $events = $this->calendarService->events->listEvents(
                config('services.google.calendar_id'),
                [
                    'timeMin' => $start->toRfc3339String(),
                    'timeMax' => $end->toRfc3339String(),
                    'singleEvents' => true,
                    'orderBy' => 'startTime',
                    'maxResults' => 2500
                ]
            );

            $items = $events->getItems();
            Log::info('Événements récupérés avec succès', [
                'count' => count($items),
                'nextPageToken' => $events->getNextPageToken()
            ]);

            return $items;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des événements Google Calendar', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'start' => $start->toRfc3339String(),
                'end' => $end->toRfc3339String()
            ]);
            return [];
        }
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

    /**
     * 📊 Récupère les services populaires avec historique
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getServicesPopulaires(Request $request)
    {
        try {
            $period = $request->get('period', 3); // Par défaut 3 mois
            $endDate = Carbon::now()->endOfDay();
            $startDate = Carbon::now()->subMonths($period)->startOfDay();

            Log::info('Récupération des services populaires', [
                'period' => $period,
                'startDate' => $startDate->toRfc3339String(),
                'endDate' => $endDate->toRfc3339String()
            ]);

            $events = $this->getEvents($startDate, $endDate);
            Log::info('Événements récupérés pour services populaires', ['count' => count($events)]);

            $filteredEvents = collect($events)->filter(function ($event) {
                if (!$event->getStart() || !$event->getStart()->getDateTime()) {
                    return false;
                }
                $summary = $event->getSummary();
                return stripos($summary, 'effynails') !== false;
            });
            Log::info('Événements filtrés pour services populaires', ['count' => $filteredEvents->count()]);

            $months = [];
            for ($i = 0; $i < $period; $i++) {
                $month = Carbon::now()->subMonths($i);
                $months[$month->format('F Y')] = 0;
            }

            $services = [];
            foreach ($filteredEvents as $event) {
                $date = Carbon::parse($event->getStart()->getDateTime());
                $monthKey = $date->format('F Y');
                $summary = $event->getSummary();
                if (preg_match('/^(.*?) entre Effynails et/i', $summary, $matches)) {
                    $serviceType = trim($matches[1]);
                    if (!isset($services[$serviceType])) {
                        $services[$serviceType] = [
                            'nom' => $serviceType,
                            'total' => 0,
                            'historique' => $months
                        ];
                    }
                    $services[$serviceType]['total']++;
                    if (isset($services[$serviceType]['historique'][$monthKey])) {
                        $services[$serviceType]['historique'][$monthKey]++;
                    }
                } else {
                    Log::warning('Service non reconnu dans un événement', ['summary' => $summary]);
                }
            }

            $services = collect($services)
                ->sortByDesc('total')
                ->values()
                ->all();

            Log::info('Services populaires calculés', ['count' => count($services)]);

            return response()->json($services);
        } catch (\Exception $e) {
            Log::error('Erreur dans getServicesPopulaires', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([], 500);
        }
    }
}
