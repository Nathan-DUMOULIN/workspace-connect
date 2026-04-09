<?php
// Paramètres de connexion (par défaut sur XAMPP/WAMP)
$host = 'localhost';
$db   = 'workspace'; // Le nom de ta base de données
$user = 'root';      // Utilisateur par défaut
$pass = '';          // Mot de passe (vide sur Windows, 'root' sur Mac)
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Affiche les erreurs
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retourne les données sous forme de tableau associatif
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "Connexion réussie à la base de données '$db' !";
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>
