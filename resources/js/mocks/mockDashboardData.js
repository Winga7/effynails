// Paramètre global pour activer/désactiver les données mock
export const useMockData = true;

// Données factices pour le dashboard Effynails

export const statsMock = {
    rendezVousAujourdhui: 5,
    rendezVousSemaine: 25,
    clientsNouveaux: 8,
    revenusMois: 2500,
};

export const derniersRendezVousMock = [
    {
        id: 1,
        client: "Marie Dupont",
        service: "Manucure",
        date: "14:00",
        statut: "confirmé",
    },
    {
        id: 2,
        client: "Sophie Martin",
        service: "Pédicure",
        date: "15:30",
        statut: "en attente",
    },
    {
        id: 3,
        client: "Julie Bernard",
        service: "Nail Art",
        date: "16:45",
        statut: "confirmé",
    },
    {
        id: 4,
        client: "Emma Leroy",
        service: "Pose de vernis",
        date: "17:30",
        statut: "confirmé",
    },
    {
        id: 5,
        client: "Chloé Petit",
        service: "Dépose",
        date: "18:00",
        statut: "en attente",
    },
];

export const servicesPopulairesMock = [
    { nom: "Manucure", nombre: 45 },
    { nom: "Pédicure", nombre: 32 },
    { nom: "Nail Art", nombre: 28 },
    { nom: "Pose de vernis", nombre: 22 },
    { nom: "Dépose", nombre: 15 },
];
