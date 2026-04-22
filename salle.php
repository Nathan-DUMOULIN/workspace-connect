<?php
require_once 'config/database.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM salle WHERE id_salle = ?");
$stmt->execute([$id]);
$salle = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/nav.css">
  <title><?= htmlspecialchars($salle['nom']) ?></title>
</head>

<body>

<header>
    <h1>Nos salles</h1>
    <p>Détails de la salle sélectionnée</p>
</header>

<nav class="navbar">
    <ul>
        <li><a href="index.php">Accueil</a></li>
        <li><a href="salles.php">Nos salles</a></li>
        <li><a href="reservation.php">Réserver</a></li>
        <li><a href="contact.php">Contact</a></li>
    </ul>
</nav>

<main class="container">

    <h1><?= htmlspecialchars($salle['nom']) ?></h1>

    <div class="salle-detail-grid">

        <!-- IMAGE -->
        <figure class="salle-detail">
            <img src="images/<?= htmlspecialchars($salle['image']) ?>" alt="<?= htmlspecialchars($salle['nom']) ?>">

            <?php if (!empty($salle['description_image'])) : ?>
                <figcaption><?= htmlspecialchars($salle['description_image']) ?></figcaption>
            <?php endif; ?>
        </figure>

        <!-- INFOS EN CARD -->
        <div class="salle-infos">
            <div class="info-item">
                <strong>Capacité :</strong> <?= $salle['capacite'] ?> personnes
            </div>

            <div class="info-item">
                <strong>Prix :</strong> <?= $salle['prix'] ?> € / heure
            </div>

            <div class="salle-actions">
                <a class="btn-discover" href="reservation.php?id=<?= $salle['id_salle'] ?>">
                    Réserver cette salle
                </a>

                <a class="btn-discover" href="salles.php">
                    ← Retour
                </a>
            </div>
        </div>

    </div>

    <!-- DESCRIPTION -->
    <div class="salle-description">
        <p><?= nl2br(htmlspecialchars($salle['description'])) ?></p>
    </div>

</main>

<footer>
    <p>© 2026 - NM WORKSPACE</p>
</footer>

</body>
</html>
