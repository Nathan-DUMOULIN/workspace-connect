<?php
// On démarre la session tout en haut
session_start();

// Inclusion du fichier de configuration de la base de données (connexion PDO)
require_once 'config/database.php';

// Tableau pour stocker les erreurs de validation
$errors = [];

// ── Traitement du formulaire POST 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ── 1. Récupération et nettoyage des données 
    $nom          = trim($_POST['nom']        ?? '');
    $prenom       = trim($_POST['prenom']     ?? '');
    $email        = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $id_salle     = intval($_POST['id_salle']      ?? 0);
    $date_resa    = trim($_POST['date_resa']   ?? '');
    $creneau      = trim($_POST['creneau']     ?? '');
    $nb_personnes = intval($_POST['nb_personnes'] ?? 0);

    // ── 2. Validation des champs
    if (empty($nom))          $errors[] = "Le nom est obligatoire.";
    if (empty($prenom))       $errors[] = "Le prénom est obligatoire.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "L'adresse email est invalide.";
    if ($id_salle <= 0)       $errors[] = "Veuillez sélectionner une salle.";
    if (empty($date_resa))    $errors[] = "La date est obligatoire.";
    if (empty($creneau))      $errors[] = "Le créneau est obligatoire.";
    if ($nb_personnes <= 0)   $errors[] = "Le nombre de personnes doit être supérieur à 0.";

    // ── 3. Insertion en base si aucune erreur
    if (empty($errors)) {
        try {
            $sql = 'INSERT INTO reservation
                        (nom, prenom, email, id_salle, date_resa, creneau, nb_personnes)
                    VALUES (?, ?, ?, ?, ?, ?, ?)';

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

            // ── 4. Enregistrement dans la SESSION pour le récapitulatif
            $_SESSION['nom']          = $nom;
            $_SESSION['prenom']       = $prenom;
            $_SESSION['email']        = $email;
            $_SESSION['id_salle']     = $id_salle;
            $_SESSION['date_resa']    = $date_resa;
            $_SESSION['creneau']      = $creneau;
            $_SESSION['nb_personnes'] = $nb_personnes;

            // ── 5. Redirection vers la page de confirmation
            header('Location: confirmation.php');
            exit;

        } catch (PDOException $e) {
            $errors[] = "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    }
}

// ── Récupération des salles pour la liste déroulante
try {
    $stmt   = $pdo->query('SELECT id_salle, nom, capacite FROM salle ORDER BY nom ASC');
    $salles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $salles   = [];
    $errors[] = "Impossible de charger les salles : " . $e->getMessage();
}
?>

<!-- ── Affichage des erreurs -->
<?php if (!empty($errors)) : ?>
    <div class="alert alert-error">
        <ul>
            <?php foreach ($errors as $err) : ?>
                <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- ── Formulaire -->
<form method="POST" action="reservation.php" novalidate>

    <div class="form-group">
        <label for="nom">Nom *</label>
        <input type="text" id="nom" name="nom"
               value="<?= htmlspecialchars($nom ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label for="prenom">Prénom *</label>
        <input type="text" id="prenom" name="prenom"
               value="<?= htmlspecialchars($prenom ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label for="email">Email *</label>
        <input type="email" id="email" name="email"
               value="<?= htmlspecialchars($email ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label for="id_salle">Salle *</label>
        <select id="id_salle" name="id_salle" required>
            <option value="">-- Choisir une salle --</option>

            <?php foreach ($salles as $salle) : ?>
                <option value="<?= (int) $salle['id_salle'] ?>"
                    <?= (isset($id_salle) && $id_salle === (int) $salle['id_salle']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($salle['nom']) ?> (<?= (int) $salle['capacite'] ?> pers.)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="date_resa">Date *</label>
        <input type="date" id="date_resa" name="date_resa"
               value="<?= htmlspecialchars($date_resa ?? '') ?>"
               min="<?= date('Y-m-d') ?>" required>
    </div>

    <div class="form-group">
        <label for="creneau">Créneau *</label>
        <select id="creneau" name="creneau" required>
            <option value="">-- Choisir un créneau --</option>
            <?php
            $creneaux = ['09:00-11:00', '11:00-13:00', '14:00-16:00', '16:00-18:00'];
            foreach ($creneaux as $c) : ?>
                <option value="<?= $c ?>" <?= (isset($creneau) && $creneau === $c) ? 'selected' : '' ?>>
                    <?= $c ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="nb_personnes">Nombre de personnes *</label>
        <input type="number" id="nb_personnes" name="nb_personnes"
               value="<?= isset($nb_personnes) ? (int) $nb_personnes : '' ?>"
               min="1" required>
    </div>

    <button type="submit">Réserver</button>

</form>
