<?php
session_start();
require_once 'config/database.php';

$nom          = isset($_SESSION['nom'])          ? htmlspecialchars($_SESSION['nom'])          : '';
$prenom       = isset($_SESSION['prenom'])       ? htmlspecialchars($_SESSION['prenom'])       : '';
$email        = isset($_SESSION['email'])        ? htmlspecialchars($_SESSION['email'])        : '';
$date_resa    = isset($_SESSION['date_resa'])    ? htmlspecialchars($_SESSION['date_resa'])    : '';
$creneau      = isset($_SESSION['creneau'])      ? htmlspecialchars($_SESSION['creneau'])      : '';
$nb_personnes = isset($_SESSION['nb_personnes']) ? (int)$_SESSION['nb_personnes']             : '';
$id_salle     = isset($_SESSION['id_salle'])     ? (int)$_SESSION['id_salle']                 : 0;

// Récupérer le nom de la salle
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
    <title>Réservation confirmée — WorkSpace Connect</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <header>
        <a href="index.php">WorkSpace Connect</a>
        <nav>
            <a href="index.php">Accueil</a>
            <a href="salles.php">Nos salles</a>
            <a href="reservation.php">Réserver</a>
        </nav>
    </header>

    <main>

        <h1>Réservation confirmée !</h1>
        <p>Votre demande a bien été enregistrée.</p>

        <h2>Récapitulatif</h2>

        <ul>
            <?php if ($nom_salle) : ?>
                <li>Salle : <?= $nom_salle ?></li>
            <?php endif; ?>

            <?php if ($nom || $prenom) : ?>
                <li>Réservé par : <?= $prenom ?> <?= $nom ?></li>
            <?php endif; ?>

            <?php if ($email) : ?>
                <li>E-mail : <?= $email ?></li>
            <?php endif; ?>

            <?php if ($date_resa) : ?>
                <li>Date : <?= date('d/m/Y', strtotime($date_resa)) ?></li>
            <?php endif; ?>

            <?php if ($creneau) : ?>
                <li>Créneau : <?= $creneau ?></li>
            <?php endif; ?>

            <?php if ($nb_personnes) : ?>
                <li>Nombre de personnes : <?= $nb_personnes ?></li>
            <?php endif; ?>
        </ul>

        <a href="salles.php">← Retour aux salles</a>
        <a href="reservation.php">Nouvelle réservation</a>

    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> WorkSpace Connect — Tous droits réservés</p>
    </footer>

</body>
</html>
