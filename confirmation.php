<?php
session_start();
require_once 'config/database.php'; 

$nom          = htmlspecialchars($_SESSION['nom'] ?? '');
$prenom       = htmlspecialchars($_SESSION['prenom'] ?? '');
$email        = htmlspecialchars($_SESSION['email'] ?? '');
$date_resa    = htmlspecialchars($_SESSION['date_resa'] ?? '');
$creneau      = htmlspecialchars($_SESSION['creneau'] ?? '');
$nb_personnes = (int)($_SESSION['nb_personnes'] ?? 0);
$id_salle     = (int)($_SESSION['id_salle'] ?? 0);

$nom_salle = '';

if ($id_salle > 0) {
    $stmt = $pdo->prepare("SELECT nom FROM salle WHERE id_salle = ?");
    $stmt->execute([$id_salle]);
    $salle = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($salle) {
        $nom_salle = htmlspecialchars($salle['nom']);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation confirmée</title>

    <link rel="stylesheet" href="css/nav.css">
</head>

<body>


<nav class="navbar">
    <ul>
        <li><a href="index.php">Accueil</a></li>
        <li><a href="salles.php">Nos salles</a></li>
        <li><a href="reservation.php">Réserver</a></li>
        <li><a href="contact.php">Contact</a></li>
    </ul>
</nav>

<h1>Réservation confirmée</h1>


<div class="container">

    <div class="recap-container">

        <h2>Récapitulatif de votre réservation</h2>

        <div class="recap-item"><strong>Salle :</strong> <?= $nom_salle ?></div>
        <div class="recap-item"><strong>Nom :</strong> <?= $prenom ?> <?= $nom ?></div>
        <div class="recap-item"><strong>Email :</strong> <?= $email ?></div>

        <?php if ($date_resa) : ?>
            <div class="recap-item">
                <strong>Date :</strong> <?= date('d/m/Y', strtotime($date_resa)) ?>
            </div>
        <?php endif; ?>

        <div class="recap-item"><strong>Créneau :</strong> <?= $creneau ?></div>
        <div class="recap-item"><strong>Personnes :</strong> <?= $nb_personnes ?></div>

        <div class="recap-actions">
            <a href="salles.php" class="btn-discover">Retour aux salles</a>
            <a href="reservation.php" class="btn-discover">Nouvelle réservation</a>
        </div>

    </div>

</div>

<footer>
    <p>&copy; <?= date('Y') ?> WORKSPACE CONNECT</p>
</footer>

</body>
</html>
