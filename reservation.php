<?php
require_once __DIR__ . '/config/database.php';

$message = ""; // message de confirmation

// Récupération des salles pour la liste déroulante
$sql = "SELECT id_salle, nom FROM salle ORDER BY nom";
$stmt = $pdo->query($sql);
$salles = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom          = trim($_POST['nom']);
    $prenom       = trim($_POST['prenom']);
    $email        = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $id_salle     = intval($_POST['id_salle']);
    $date_resa    = $_POST['date_resa'];
    $creneau      = trim($_POST['creneau']);
    $nb_personnes = intval($_POST['nb_personnes']);

    if ($nom && $prenom && $email && $id_salle && $date_resa) {

        $sql = "INSERT INTO reservation
                (nom, prenom, email, id_salle, date_resa, creneau, nb_personnes)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $nom, $prenom, $email,
            $id_salle, $date_resa, $creneau, $nb_personnes
        ]);

        // Message de confirmation
        $message = "Votre réservation a bien été enregistrée !";
    } else {
        $message = "Veuillez remplir tous les champs obligatoires.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réserver une salle</title>
</head>
<body>

<h1>Formulaire de réservation</h1>

<?php if (!empty($message)) : ?>
    <p style="padding:10px; border:1px solid #000; background:#e0ffe0;">
        <?= htmlspecialchars($message) ?>
    </p>
<?php endif; ?>

<form method="POST" action="reservation.php">

    <label>Nom :</label><br>
    <input type="text" name="nom" required><br><br>

    <label>Prénom :</label><br>
    <input type="text" name="prenom" required><br><br>

    <label>Email :</label><br>
    <input type="email" name="email" required><br><br>

    <label>Salle :</label><br>
    <select name="id_salle" required>
        <option value="">-- Choisir une salle --</option>
        <?php foreach ($salles as $salle): ?>
            <option value="<?= $salle['id_salle'] ?>">
                <?= htmlspecialchars($salle['nom']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Date de réservation :</label><br>
    <input type="date" name="date_resa" required><br><br>

    <label>Créneau :</label><br>
    <input type="text" name="creneau" placeholder="Ex : 14h - 18h"><br><br>

    <label>Nombre de personnes :</label><br>
    <input type="number" name="nb_personnes" min="1"><br><br>

    <button type="submit">Réserver</button>

</form>

</body>
</html>


