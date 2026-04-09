<?php
require_once 'config/database.php';

// Récupérer toutes les salles avec gestion d'erreurs
try {
    $stmt = $pdo->prepare('SELECT * FROM salle ORDER BY nom ASC');
    $stmt->execute();
    $salles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de récupération des salles : " . $e->getMessage());
}
?>

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
                <h2><?= htmlspecialchars($salle['nom']) ?></h2>
                <p>Capacité : <?= (int) $salle['capacite'] ?> personnes</p>
                <p><?= number_format((float) $salle['prix'], 2) ?> € / heure</p>
                <a href="salle.php?id=<?= (int) $salle['id_salle'] ?>">Voir la salle</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
