<?php
session_start();
require_once 'config/database.php';

$errors = [];

/* ===== TRAITEMENT FORMULAIRE ===== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom          = trim($_POST['nom'] ?? '');
    $prenom       = trim($_POST['prenom'] ?? '');
    $email        = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $id_salle     = intval($_POST['id_salle'] ?? 0);
    $date_resa    = trim($_POST['date_resa'] ?? '');
    $creneau      = trim($_POST['creneau'] ?? '');
    $nb_personnes = intval($_POST['nb_personnes'] ?? 0);

    if (empty($nom)) $errors[] = "Le nom est obligatoire.";
    if (empty($prenom)) $errors[] = "Le prénom est obligatoire.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide.";
    if ($id_salle <= 0) $errors[] = "Choisissez une salle.";
    if (empty($date_resa)) $errors[] = "Date obligatoire.";
    if (empty($creneau)) $errors[] = "Créneau obligatoire.";
    if ($nb_personnes <= 0) $errors[] = "Nombre de personnes invalide.";

    if (empty($errors)) {
        try {
            $sql = "INSERT INTO reservation
                    (nom, prenom, email, id_salle, date_resa, creneau, nb_personnes)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $nom,
                $prenom,
                $email,
                $id_salle,
                $date_resa,
                $creneau,
                $nb_personnes
            ]);

            $_SESSION = [
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'id_salle' => $id_salle,
                'date_resa' => $date_resa,
                'creneau' => $creneau,
                'nb_personnes' => $nb_personnes
            ];

            header('Location: confirmation.php');
            exit;

        } catch (PDOException $e) {
            $errors[] = "Erreur SQL : " . $e->getMessage();
        }
    }
}

/* ===== SALLES ===== */
try {
    $stmt = $pdo->query("SELECT id_salle, nom, capacite FROM salle ORDER BY nom ASC");
    $salles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $salles = [];
    $errors[] = "Erreur chargement salles.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réserver une salle</title>
    <link rel="stylesheet" href="css/nav.css">
</head>

<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar">
    <ul>
        <li><a href="index.php">Accueil</a></li>
        <li><a href="salles.php">Nos salles</a></li>
        <li><a href="reservation.php" class="active">Réserver</a></li>
        <li><a href="contact.php">Contact</a></li>
    </ul>
</nav>

<h1>Réserver une salle</h1>

<div class="container">

<!-- ===== ERREURS ===== -->
<?php if (!empty($errors)) : ?>
    <div class="alert">
        <ul>
            <?php foreach ($errors as $err) : ?>
                <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- ===== FORMULAIRE ===== -->
<form method="POST" action="reservation.php">

    <div class="form-group">
        <label>Nom</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($nom ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label>Prénom</label>
        <input type="text" name="prenom" value="<?= htmlspecialchars($prenom ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label>Salle</label>
        <select name="id_salle" required>
            <option value="">-- Choisir --</option>
            <?php foreach ($salles as $s) : ?>
                <option value="<?= $s['id_salle'] ?>">
                    <?= htmlspecialchars($s['nom']) ?> (<?= $s['capacite'] ?> pers.)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Date</label>
        <input type="date" name="date_resa" min="<?= date('Y-m-d') ?>" required>
    </div>

    <div class="form-group">
        <label>Créneau</label>
        <select name="creneau" required>
            <option value="">-- Choisir --</option>
            <option>09:00-11:00</option>
            <option>11:00-13:00</option>
            <option>14:00-16:00</option>
            <option>16:00-18:00</option>
        </select>
    </div>

    <div class="form-group">
        <label>Nombre de personnes</label>
        <input type="number" name="nb_personnes" min="1" required>
    </div>

    <button type="submit" class="btn-form">Réserver</button>

</form>

</div>

<footer>
    <p>© 2026 - NM WORKSPACE</p>
</footer>

</body>
</html>
