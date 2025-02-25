# Instructions pour l'Assistant Claude

## Structure technique de référence

-   Framework: Laravel 11.x
-   Frontend: Inertia.js avec Vue 3
-   CSS: Tailwind CSS
-   Layout principal: `AppLayout.vue`

## Règles générales

### 1. Langue et Communication

-   Toujours répondre en français
-   Maintenir un ton professionnel mais accessible
-   Prendre le temps d'analyser correctement chaque question

### 2. Format des réponses

-   Utiliser le format Markdown
-   Pour le code, toujours spécifier :
    -   Le langage
    -   Le chemin complet du fichier
    -   Les sections modifiées avec "// ... existing code ..."

### 3. Navigation et Routes

-   Vérifier que toute nouvelle route est cohérente avec `routes/web.php`
-   Considérer les impacts sur :
    -   Menu principal dans `AppLayout.vue`
    -   Menu mobile (hamburger)
    -   Les composants `NavLink` et `ResponsiveNavLink`
    -   Ne plus jamais utiliser Kernel

### 4. Responsivité

-   Toutes les interfaces DOIVENT être entièrement responsives
-   Utiliser les classes Tailwind pour la gestion du responsive :
    -   `sm:` (640px et plus)
    -   `md:` (768px et plus)
    -   `lg:` (1024px et plus)
    -   `xl:` (1280px et plus)
-   Tester systématiquement sur mobile, tablette et desktop
-   Adapter les composants pour une expérience optimale sur tous les appareils

### 5. Structure des Pages

-   Utiliser `AppLayout.vue` comme layout principal
-   Structure type d'une page :

```vue
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
</script>

<template>
    <AppLayout title="Titre Page">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Titre Section
            </h2>
        </template>

        <div class="py-12">
            <!-- Contenu -->
        </div>
    </AppLayout>
</template>
```

### 6. Instructions pour les modifications

-   Toujours fournir les chemins complets des fichiers à modifier
-   Inclure les commandes terminal nécessaires
-   Expliquer brièvement les modifications proposées
-   Fournir TOUJOURS les liens vers les fichiers modifiés

### 7. Bonnes pratiques

-   Suivre les standards de développement Laravel/Vue.js
-   Assurer la responsivité de toutes les interfaces
-   Privilégier les solutions performantes et maintenables
