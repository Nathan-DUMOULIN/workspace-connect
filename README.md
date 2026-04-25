 WorkSpace Connect

Site web de réservation de salles de réunion et d’espaces de coworking.
Projet réalisé dans le cadre du BTS SIO (option SLAM) en première année.
Nathan DU MOULIN, Mevin FOFANA. 

Présentation

WorkSpace Connect est une application web qui permet aux utilisateurs de :

Voir les salles disponibles
Consulter leurs informations (capacité, équipements, prix)
Réserver une salle via un formulaire
Enregistrer les réservations dans une base de données


Objectifs

Ce projet avait pour but de :

Créer un site web dynamique en PHP
Mettre en place un système de réservation
Utiliser une base de données MySQL
Gérer les données envoyées par un formulaire


 Fonctionnalités

Le site propose :

Une page d’accueil
Une page avec la liste des salles
Une page de détail pour chaque salle
Un formulaire de réservation
Une page de confirmation après réservation
un formulaire de contact

 Technologies utilisées

 
HTML / CSS : structure et design du site
PHP : traitement des données et logique du site
MySQL : stockage des données
XAMPP : serveur local
GitHub : gestion du projet


 Sécurité

Quelques bonnes pratiques ont été mises en place :

Vérification des données côté serveur
Utilisation de requêtes préparées (PDO)
Nettoyage des données (htmlspecialchars)

 Installation :

 
Cloner le projet dans htdocs :
git clone https://github.com/ /workspace-connect

Démarrer Apache et MySQL avec XAMPP
Créer une base de données :
workspace_connect

Importer le fichier SQL dans config/
Ouvrir le site :
http://localhost/workspace-connect

 Structure du projet
workspace-connect/
│
├── index.php
├── salles.php
├── reservation.php
├── confirmation.php
│
├── config/
│   └── database.php
│
├── css/
│   └── nav.css


 Compétences utilisées

Développement web (HTML, CSS, PHP)
Utilisation d’une base de données
Gestion de formulaire
Organisation d’un projet
 

Ce projet nous a permis de mieux comprendre le fonctionnement d’un site dynamique avec PHP et une base de données, ainsi que la gestion des réservations en ligne.
