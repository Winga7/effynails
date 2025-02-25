# Instructions pour le développement avec Laravel 11.x et Inertia

## Structure technique

-   Framework: Laravel 11.x
-   Frontend: Inertia.js avec Vue 3
-   CSS: Tailwind CSS
-   Layout principal: `AppLayout.vue`

## Règles générales

### 1. Navigation

-   Toute nouvelle route doit être ajoutée dans `routes/web.php`
-   Pour chaque lien de navigation ajouté, mettre à jour :
    -   Menu principal dans `AppLayout.vue`
    -   Menu mobile (hamburger) dans la section responsive
    -   Les composants `NavLink` et `ResponsiveNavLink` correspondants
    -   N'emploie plus jamais Kernel

### 2. Layouts

-   Utiliser `AppLayout.vue` comme layout principal
-   Structure type d'une page :

### 3. Responsivité

-   Toutes les interfaces DOIVENT être entièrement responsives
-   Utiliser les classes Tailwind pour la gestion du responsive :
    -   `sm:` (640px et plus)
    -   `md:` (768px et plus)
    -   `lg:` (1024px et plus)
    -   `xl:` (1280px et plus)
-   Tester systématiquement sur mobile, tablette et desktop
-   Adapter les composants pour une expérience optimale sur tous les appareils
-   Utiliser les composants responsifs existants (ResponsiveNavLink, etc.)

### 4. Format de reponse

-   Prend le temps d'analyser correctement la question
-   Donne moi TOUJOURS les liens vers les fichier ou tu me propose des modifications.

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
