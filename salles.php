<!DOCTYPE html>
<html lang="fr"> 
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/nav.css">
  <title>Nos salles</title>
</head>

<body>

<?php
require_once 'config/database.php';

try {
    $stmt = $pdo->prepare('SELECT * FROM salle ORDER BY nom ASC');
    $stmt->execute();
    $salles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>


<nav class="navbar">
    <ul>
        <li><a href="index.php">Accueil</a></li>
        <li><a href="salles.php" class="active">Nos salles</a></li>
        <li><a href="reservation.php">Réserver</a></li>
        <li><a href="contact.php">Contact</a></li>
    </ul>
</nav>

<div class="grille-salles">

<?php if (empty($salles)) : ?>
    <p class="aucun-resultat">Aucune salle disponible pour le moment.</p>

<?php else : ?>
    <?php foreach ($salles as $salle) : ?>

        <div class="card">
            
            
            <img 
                src="images/<?= htmlspecialchars($salle['image']) ?>" 
                alt="<?= htmlspecialchars($salle['nom']) ?>"
            >

            <div class="card-content">
                <h3><?= htmlspecialchars($salle['nom']) ?></h3>

                <p> <?= (int) $salle['capacite'] ?> personnes</p>
                <p> <?= number_format((float) $salle['prix'], 2) ?> € / heure</p>

                <a href="salle.php?id=<?= (int) $salle['id_salle'] ?>" class="btn-discover">
                    Voir la salle
                </a>
            </div>

        </div>

    <?php endforeach; ?>
<?php endif; ?>

</div>

</body>
</html>
