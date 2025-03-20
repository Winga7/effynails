# Guide de rédaction des messages de commit

Ce document décrit les **règles de nommage** des messages de commit pour ce projet.  
Nous utilisons un **emoji** suivi d'un titre court en français. Le message complet ne doit pas dépasser 2 lignes.

---

## Format général

[emoji] Titre très concis (1ère ligne)
Explication courte si nécessaire (2ème ligne, optionnelle)

### Exemple minimal

🐛 Corrige le bug d'affichage des images

### Exemple complet

✨ Ajoute le composant de pagination
Inclut les tests et la documentation

---

## Liste des émojis recommandés

| Emoji | Signification                                | Exemple de titre                       |
| ----- | -------------------------------------------- | -------------------------------------- |
| 🎉    | Début d'un projet ou version majeure         | `🎉 Initialisation du projet`          |
| ✨    | Nouvelle fonctionnalité                      | `✨ Ajoute le mode sombre`             |
| 🐛    | Correction de bug                            | `🐛 Corrige l'erreur 500`              |
| 🔥    | Suppression de code ou de fichiers inutiles  | `🔥 Supprime l'ancien composant`       |
| 📝    | Documentation ou commentaires                | `📝 Met à jour le README`              |
| 🎨    | Amélioration du format/code style            | `🎨 Nettoie la mise en forme CSS`      |
| ♻️    | Refactorisation du code                      | `♻️ Simplifie la fonction de parsing`  |
| 💄    | Mise à jour de l'UI/design                   | `💄 Améliore le style du footer`       |
| ✅    | Ajout ou mise à jour de tests                | `✅ Ajoute les tests unitaires`        |
| 🔒    | Sécurité (dépendances, failles, etc.)        | `🔒 Met à jour la dépendance critique` |
| ⬆️    | Mises à jour de dépendances                  | `⬆️ Met à jour Vue.js vers la v3.2`    |
| ⬇️    | Rétrogradations de dépendances               | `⬇️ Rétrograde React`                  |
| 🚧    | En cours de développement / Work in progress | `🚧 WIP - Composant login`             |
| 🚑    | Hotfix                                       | `🚑 Corrige la faille en prod`         |
| ⚡    | Amélioration des performances                | `⚡ Optimise le chargement des images` |
| ♿    | Accessibilité                                | `♿ Ajoute les attributs ARIA`         |
| 🌐    | Internationalisation/traduction              | `🌐 Ajoute la traduction en anglais`   |

---

## Règles d'écriture

1. **Toujours commencer** par l'emoji correspondant au type de commit.
2. Le titre doit être **très court** (moins de 50 caractères).
3. **Pas de point** à la fin du titre.
4. Si nécessaire, ajouter une 2ème ligne brève pour plus de contexte.
5. **Maximum 2 lignes** pour l'ensemble du message.

---

## Bonnes pratiques

-   Faire des commits **atomiques** : un commit = un changement cohérent.
-   **Commiter fréquemment** : plusieurs petits commits plutôt qu'un gros.
-   Utiliser le préfixe `WIP:` ou l'emoji 🚧 pour les travaux en cours.

---

## Exemples concrets

### Exemple 1

✨ Ajoute l'upload d'images
Avec drag & drop et prévisualisation

### Exemple 2

🐛 Corrige la redirection après connexion
Résout l'issue #123

### Exemple 3

🎨 Réorganise le code SCSS
