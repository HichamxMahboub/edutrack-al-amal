# EduTrack Al Amal

EduTrack Al Amal est une plateforme Laravel de suivi scolaire, social et pédagogique pour l'Association Al Amal. Le projet illustre une démarche de transformation digitale appliquée à un contexte éducatif associatif: centralisation des données, digitalisation des processus internes, pilotage par indicateurs, gestion des rôles et sécurisation des accès.

## Présentation du projet
- Suivi des élèves, classes, enseignants et encadrants
- Saisie et consultation des notes
- Génération de bulletins PDF
- Messagerie interne
- Import / export des données
- Tableau de bord avec indicateurs et graphiques
- Page de démonstration du projet selon les notions du cours

## Contexte de transformation digitale
Le système remplace un suivi dispersé par fichiers et échanges manuels par une plateforme unifiée qui permet de structurer l'information, réduire les pertes de données et améliorer la coordination entre les acteurs.

## Problématique
Comment digitaliser le suivi scolaire tout en gardant un outil simple, fiable, sécurisé et utile pour la prise de décision pédagogique et sociale ?

## Objectifs
- Centraliser les données de suivi
- Donner une visibilité rapide sur les indicateurs clés
- Séparer les droits selon les rôles
- Simplifier la génération des bulletins et des exports
- Montrer les notions de transformation digitale dans un cas concret

## Fonctionnalités
- Authentification et profils utilisateur
- Rôles: administrateur, enseignant, encadrant
- Gestion des élèves avec archivage
- Gestion des classes et affectation d'enseignants
- Saisie et filtrage des notes
- Calcul de moyenne et mention
- Bulletins HTML et PDF
- Messagerie interne
- Import / export Excel
- Dashboard avec statistiques et graphiques
- Landing page publique et page "Transformation digitale"

## Stack technique
- Laravel 12
- PHP 8.2+
- SQLite
- Tailwind CSS
- Vite
- Chart.js
- Maatwebsite Excel
- barryvdh/laravel-dompdf

## Architecture fonctionnelle
- Couche publique: accueil, transformation digitale
- Couche authentifiée: dashboard, modules métier
- Couche administration: staff et import/export
- Couche documentaire: bulletins PDF
- Couche décisionnelle: statistiques et graphiques

## Installation locale
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

## Configuration SQLite
Le projet est prévu pour fonctionner avec SQLite en local.

Vérifier dans `.env`:
```env
DB_CONNECTION=sqlite
DB_DATABASE=/chemin/absolu/vers/database/database.sqlite
```

Si le fichier n'existe pas:
```bash
touch database/database.sqlite
```

## Comptes de test
- `admin@edutrack.test` / `password`
- `enseignant@edutrack.test` / `password`
- `encadrant@edutrack.test` / `password`

## Commandes utiles
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm run build
php artisan serve
php artisan test
```

## Scénario de démonstration en 10 minutes
1. Ouvrir la page d'accueil et présenter le contexte du projet.
2. Montrer la page "Transformation digitale" et expliquer les notions du cours.
3. Se connecter avec le compte administrateur.
4. Présenter le dashboard, les cartes KPI et les graphiques.
5. Ouvrir la liste des élèves et montrer la recherche, le filtre et l'archivage.
6. Ouvrir une fiche élève et exporter le bulletin PDF.
7. Ouvrir les classes et montrer l'enseignant responsable.
8. Ouvrir les notes et expliquer le calcul de moyenne.
9. Ouvrir la messagerie interne.
10. Ouvrir import/export pour montrer la logique de gestion de la donnée.

## Notions du cours appliquées
- Diagnostic de l'existant
- Stratégie digitale
- Axes de transformation
- Digitalisation des processus internes
- Gestion de la data
- Sécurité des données
- Conduite du changement
- Maturité digitale
- Pilotage par indicateurs

## Structure du projet
- `app/Http/Controllers`: logique métier
- `app/Models`: modèles et calculs
- `database/migrations`: schéma de base de données
- `database/seeders`: données de démonstration
- `resources/views`: interface utilisateur
- `resources/css` et `resources/js`: assets front
- `tests`: tests de démonstration

## Perspectives
- Alertes automatiques sur les élèves à risque
- Historique plus détaillé des changements
- Export PDF plus avancé
- Notifications en temps réel
- Tableau de bord enrichi par semestre et par classe

## Captures d'écran à ajouter
- Page d'accueil
- Dashboard
- Liste des élèves
- Fiche classe
- Bulletin PDF
- Messagerie interne
- Page transformation digitale
