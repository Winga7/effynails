# EFFYNAILS - Site Web Professionnel de Prothèse Ongulaire

<p align="center">
  <img src="public/images/logo.png" alt="Logo EFFYNAILS" width="200">
</p>

## À propos

EFFYNAILS est un site web professionnel pour une prothésiste ongulaire indépendante. Le site présente les services proposés, un portfolio des réalisations, et offre aux clients la possibilité de prendre rendez-vous en ligne.

## Fonctionnalités principales

- **Présentation des services et tarifs**
- **Portfolio des réalisations**
- **Système de prise de rendez-vous en ligne**
- **Témoignages clients**
- **Espace administrateur pour gérer les rendez-vous et les contenus**
- **Design responsive adapté à tous les appareils**

## Technologies utilisées

- **Frontend**: Vue.js 3, Inertia.js, TailwindCSS
- **Backend**: Laravel 10
- **Base de données**: MySQL
- **Système d'authentification**: Laravel Breeze/Jetstream

## Installation

### Prérequis

- PHP 8.1 ou supérieur
- Composer
- Node.js et NPM
- MySQL ou MariaDB

### Étapes d'installation

1. **Cloner le dépôt**
   ```bash
   git clone https://github.com/votre-compte/effynails.git
   cd effynails
   ```

2. **Installer les dépendances PHP**
   ```bash
   composer install
   ```

3. **Installer les dépendances JavaScript**
   ```bash
   npm install
   ```

4. **Configurer le fichier d'environnement**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configurer la base de données**
   - Modifiez le fichier `.env` pour y mettre vos informations de connexion à la base de données

6. **Migrer la base de données**
   ```bash
   php artisan migrate --seed
   ```

7. **Créer un lien symbolique pour le stockage**
   ```bash
   php artisan storage:link
   ```

8. **Compiler les assets**
   ```bash
   npm run dev
   ```
   
   Pour la production :
   ```bash
   npm run build
   ```

9. **Démarrer le serveur de développement**
   ```bash
   php artisan serve
   ```

## Structure du projet

- `app/` - Le cœur de l'application Laravel
- `resources/js/` - Composants Vue.js et logique frontend
- `resources/css/` - Styles CSS et configurations TailwindCSS
- `resources/js/Pages/` - Pages principales du site
- `resources/js/Components/` - Composants réutilisables
- `database/migrations/` - Migrations de base de données
- `public/` - Fichiers accessibles publiquement

## Documentation

Pour plus de détails sur l'architecture et le fonctionnement du site :

- [Diagramme de Classes](docs/DiagrammeClasses.md)
- [Manuel d'utilisation](docs/ManuelUtilisation.md)

## Déploiement

Le site peut être déployé sur n'importe quel hébergement supportant PHP 8.1+ et MySQL. Pour des performances optimales, nous recommandons :

- Serveur dédié ou VPS
- Serveur web nginx ou Apache
- Certificat SSL (Let's Encrypt)
- Cache opcode PHP activé

## Maintenance

Pour maintenir le site à jour :

1. Appliquer régulièrement les mises à jour de sécurité Laravel
2. Maintenir à jour les dépendances NPM
3. Faire des sauvegardes régulières de la base de données
4. Surveiller les logs d'erreurs

## Licence

Ce projet est propriétaire et destiné exclusivement à l'usage d'EFFYNAILS.

## Contact

Pour toute question concernant le développement ou la maintenance de ce site :
- Email de développement: andywinga@hotmail.be
