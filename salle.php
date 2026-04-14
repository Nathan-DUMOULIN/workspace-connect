<?php
require_once 'config/database.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM salle WHERE id_salle = ?");
$stmt->execute([$id]);
$salle = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<h1><?= htmlspecialchars($salle['nom']) ?></h1>

<!-- ↓ ICI : l'image ET sa description juste en dessous -->
<figure>
    <img src="images/<?= htmlspecialchars($salle['image']) ?>" alt="">

    <?php if (!empty($salle['description_image'])) : ?>
        <figcaption><?= htmlspecialchars($salle['description_image']) ?></figcaption>
    <?php endif; ?>
</figure>
<!-- ↑ FIN du bloc image + description -->

<p>Capacité : <?= $salle['capacite'] ?> personnes</p>
<p><?= $salle['prix'] ?> € / heure</p>
<p><?= htmlspecialchars($salle['description']) ?></p>

<a href="reservation.php?id=<?= $salle['id_salle'] ?>">Réserver cette salle</a>
<a href="salles.php">← Retour</a>
