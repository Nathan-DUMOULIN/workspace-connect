<?php
// Inclusion du fichier de configuration de la base de données (connexion PDO)
require_once 'config/database.php';

// Tableau pour stocker les erreurs de validation
$errors = [];

// ── Traitement du formulaire POST 

// Vérifie que le formulaire a bien été soumis en méthode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ── 1. Récupération et nettoyage des données 

    // trim() supprime les espaces en début/fin de chaîne | ?? '' évite une erreur si la clé n'existe pas
    $nom          = trim($_POST['nom']        ?? '');
    $prenom       = trim($_POST['prenom']     ?? '');

    // filter_var + FILTER_SANITIZE_EMAIL supprime les caractères illégaux dans l'email
    $email        = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);

    // intval() force la conversion en entier, empêche toute injection de texte
    $id_salle     = intval($_POST['id_salle']      ?? 0);

    // Récupération de la date de réservation
    $date_resa    = trim($_POST['date_resa']   ?? '');

    // Récupération du créneau horaire choisi
    $creneau      = trim($_POST['creneau']     ?? '');

    // intval() garantit un nombre entier pour le nombre de personnes
    $nb_personnes = intval($_POST['nb_personnes'] ?? 0);

    // ── 2. Validation des champs

    // Vérifie que le nom n'est pas vide après nettoyage
    if (empty($nom))
        $errors[] = "Le nom est obligatoire.";

    // Vérifie que le prénom n'est pas vide après nettoyage
    if (empty($prenom))
        $errors[] = "Le prénom est obligatoire.";

    // FILTER_VALIDATE_EMAIL vérifie le format de l'adresse email (ex: user@domain.com)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = "L'adresse email est invalide.";

    // L'id_salle doit être un entier positif (0 = aucune salle sélectionnée)
    if ($id_salle <= 0)
        $errors[] = "Veuillez sélectionner une salle.";

    // Vérifie que la date de réservation est bien renseignée
    if (empty($date_resa))
        $errors[] = "La date est obligatoire.";

    // Vérifie que le créneau horaire est bien sélectionné
    if (empty($creneau))
        $errors[] = "Le créneau est obligatoire.";

    // Le nombre de personnes doit être au moins 1
    if ($nb_personnes <= 0)
        $errors[] = "Le nombre de personnes doit être supérieur à 0.";

    // ── 3. Insertion en base si aucune erreur ──────────────────────────────

    // On n'insère que si le tableau d'erreurs est vide
    if (empty($errors)) {
        try {
            // Requête préparée avec des marqueurs "?" pour éviter les injections SQL
            $sql = 'INSERT INTO reservation
                        (nom, prenom, email, id_salle, date_resa, creneau, nb_personnes)
                    VALUES (?, ?, ?, ?, ?, ?, ?)';

            // Prépare la requête SQL (analyse et compile la requête sans l'exécuter)
            $stmt = $pdo->prepare($sql);

            // Exécute la requête en passant les valeurs dans l'ordre des "?"
            // PDO échappe automatiquement chaque valeur → protection contre les injections SQL
            $stmt->execute([
                $nom,          // 1er "?" → nom
                $prenom,       // 2ème "?" → prénom
                $email,        // 3ème "?" → email
                $id_salle,     // 4ème "?" → id de la salle
                $date_resa,    // 5ème "?" → date de réservation
                $creneau,      // 6ème "?" → créneau horaire
                $nb_personnes  // 7ème "?" → nombre de personnes
            ]);

            // ── 4. Redirection après succès ────────────────────────────────

            // Redirige vers la page de confirmation après insertion réussie
            header('Location: confirmation.php');

            // Stoppe l'exécution du script pour éviter que du code s'exécute après la redirection
            exit;

        } catch (PDOException $e) {
            // En cas d'erreur PDO, on ajoute le message dans le tableau d'erreurs
            $errors[] = "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    }
}

// ── Récupération des salles pour la liste déroulante ──────────────────────

try {
    // Récupère toutes les salles triées par nom pour alimenter le <select>
    $stmt   = $pdo->query('SELECT id_salle, nom, capacite FROM salle ORDER BY nom ASC');

    // fetchAll retourne toutes les lignes sous forme de tableau associatif
    $salles = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Si la requête échoue, on initialise un tableau vide pour éviter une erreur dans le foreach
    $salles   = [];
    $errors[] = "Impossible de charger les salles : " . $e->getMessage();
}
?>

<!-- ── Affichage des erreurs ─────────────────────────────────────────────── -->

<?php if (!empty($errors)) : ?>
    <div class="alert alert-error">
        <ul>
            <?php foreach ($errors as $err) : ?>
                <!-- htmlspecialchars() protège contre les attaques XSS dans les messages d'erreur -->
                <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- ── Formulaire ────────────────────────────────────────────────────────── -->

<!-- method="POST" envoie les données de façon sécurisée | novalidate désactive la validation HTML5 native (on gère nous-mêmes) -->
<form method="POST" action="reservation.php" novalidate>

    <div class="form-group">
        <label for="nom">Nom *</label>
        <!-- value="" repopule le champ si le formulaire est resoumis avec des erreurs | htmlspecialchars() protège contre le XSS -->
        <input type="text" id="nom" name="nom"
               value="<?= htmlspecialchars($nom ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label for="prenom">Prénom *</label>
        <!-- Même principe : on réaffiche la valeur saisie en cas d'erreur -->
        <input type="text" id="prenom" name="prenom"
               value="<?= htmlspecialchars($prenom ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label for="email">Email *</label>
        <!-- type="email" active le clavier email sur mobile -->
        <input type="email" id="email" name="email"
               value="<?= htmlspecialchars($email ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label for="id_salle">Salle *</label>
        <select id="id_salle" name="id_salle" required>
            <!-- Option par défaut non sélectionnable (value vide) -->
            <option value="">-- Choisir une salle --</option>

            <?php foreach ($salles as $salle) : ?>
                <option value="<?= (int) $salle['id_salle'] ?>"
                    <?php
                    // Reséectionne automatiquement la salle choisie si le formulaire est resoumis
                    echo (isset($id_salle) && $id_salle === (int) $salle['id_salle']) ? 'selected' : '';
                    ?>>
                    <!-- htmlspecialchars() protège le nom de la salle contre le XSS -->
                    <?= htmlspecialchars($salle['nom']) ?>
                    <!-- Affiche la capacité entre parenthèses pour aider l'utilisateur -->
                    (<?= (int) $salle['capacite'] ?> pers.)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="date_resa">Date *</label>
        <!-- min= empêche de sélectionner une date passée | date('Y-m-d') retourne la date du jour -->
        <input type="date" id="date_resa" name="date_resa"
               value="<?= htmlspecialchars($date_resa ?? '') ?>"
               min="<?= date('Y-m-d') ?>" required>
    </div>

    <div class="form-group">
        <label for="creneau">Créneau *</label>
        <select id="creneau" name="creneau" required>
            <option value="">-- Choisir un créneau --</option>
            <?php
            // Tableau des créneaux horaires disponibles
            $creneaux = ['09:00-11:00', '11:00-13:00', '14:00-16:00', '16:00-18:00'];

            foreach ($creneaux as $c) : ?>
                <option value="<?= $c ?>"
                    <?php
                    // Reséectionne le créneau précédemment choisi en cas de resoumission
                    echo (isset($creneau) && $creneau === $c) ? 'selected' : '';
                    ?>>
                    <?= $c /* Affiche le créneau ex: "09:00-11:00" */ ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="nb_personnes">Nombre de personnes *</label>
        <!-- type="number" + min="1" empêche la saisie de valeurs négatives ou nulles -->
        <input type="number" id="nb_personnes" name="nb_personnes"
               value="<?= isset($nb_personnes) ? (int) $nb_personnes : '' ?>"
               min="1" required>
    </div>

    <!-- Bouton de soumission du formulaire -->
    <button type="submit">Réserver</button>

</form>


