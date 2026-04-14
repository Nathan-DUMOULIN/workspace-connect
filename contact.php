<?php
// Inclusion de la connexion PDO à la base de données
require_once 'config/database.php';

// Tableau pour stocker les erreurs de validation
$errors = [];

// Tableau pour conserver les valeurs saisies en cas d'erreur (repopulation des champs)
$values = [
    'nom'     => '',
    'email'   => '',
    'sujet'   => '',
    'message' => '',
];

// Variable pour afficher le message de succès après insertion
$success = false;

// ── Traitement du formulaire POST ──────────────────────────────────────────

// Vérifie que le formulaire a bien été soumis en méthode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ── 1. Récupération et nettoyage des données ───────────────────────────

    // trim() supprime les espaces inutiles | ?? '' évite une erreur PHP si la clé est absente
    $values['nom']     = trim($_POST['nom']     ?? '');

    // filter_var + FILTER_SANITIZE_EMAIL supprime les caractères illégaux dans l'email
    $values['email']   = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);

    // Récupération du sujet du message
    $values['sujet']   = trim($_POST['sujet']   ?? '');

    // Récupération du contenu du message
    $values['message'] = trim($_POST['message'] ?? '');

    // ── 2. Validation des champs ───────────────────────────────────────────

    // Le nom doit contenir au moins 2 caractères
    if (strlen($values['nom']) < 2)
        $errors[] = "Le nom doit contenir au moins 2 caractères.";

    // FILTER_VALIDATE_EMAIL vérifie le format de l'adresse (ex: user@domain.com)
    if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL))
        $errors[] = "L'adresse email est invalide.";

    // Le sujet ne doit pas être vide
    if (empty($values['sujet']))
        $errors[] = "Le sujet est obligatoire.";

    // Le message doit contenir au moins 10 caractères pour être exploitable
    if (strlen($values['message']) < 10)
        $errors[] = "Le message doit contenir au moins 10 caractères.";

    // ── 3. Insertion en base si aucune erreur ──────────────────────────────

    // On n'insère que si le tableau d'erreurs est vide
    if (empty($errors)) {
        try {
            // Requête préparée avec marqueurs "?" → protection contre les injections SQL
            $sql = 'INSERT INTO contact (nom, email, sujet, message, date_envoi)
                    VALUES (?, ?, ?, ?, NOW())';

            // Prépare la requête (analyse et compile sans l'exécuter)
            $stmt = $pdo->prepare($sql);

            // Exécute la requête en passant les valeurs assainies dans l'ordre des "?"
            $stmt->execute([
                $values['nom'],     // 1er "?" → nom de l'expéditeur
                $values['email'],   // 2ème "?" → email de l'expéditeur
                $values['sujet'],   // 3ème "?" → sujet du message
                $values['message'], // 4ème "?" → contenu du message
                // NOW() est géré directement en SQL pour la date d'envoi automatique
            ]);

            // Réinitialise les champs après succès pour vider le formulaire
            $values = ['nom' => '', 'email' => '', 'sujet' => '', 'message' => ''];

            // Active l'affichage du message de succès
            $success = true;

        } catch (PDOException $e) {
            // En cas d'erreur PDO, on l'ajoute dans le tableau d'erreurs
            $errors[] = "Erreur lors de l'envoi : " . $e->getMessage();
        }
    }
}
?>

<!-- ── Message de succès ─────────────────────────────────────────────────── -->
<?php if ($success) : ?>
    <div class="alert alert-success">
        <!-- Confirmation que le message a bien été enregistré -->
        <p><strong>Message envoyé !</strong> Merci, nous vous répondrons dans les plus brefs délais.</p>
    </div>
<?php endif; ?>

<!-- ── Affichage des erreurs ─────────────────────────────────────────────── -->
<?php if (!empty($errors)) : ?>
    <div class="alert alert-error">
        <p><strong>Veuillez corriger les erreurs suivantes :</strong></p>
        <ul>
            <?php foreach ($errors as $err) : ?>
                <!-- htmlspecialchars() protège contre les attaques XSS dans les messages d'erreur -->
                <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- ── Formulaire de contact ─────────────────────────────────────────────── -->
<!-- method="POST" : envoi sécurisé | novalidate : on gère la validation côté PHP -->
<form method="POST" action="contact.php" novalidate>

    <!-- Champ Nom -->
    <div class="form-group">
        <label for="nom">Nom *</label>
        <!-- value= repopule le champ si le formulaire est resoumis avec des erreurs -->
        <!-- htmlspecialchars() protège contre le XSS dans la valeur réaffichée -->
        <input type="text" id="nom" name="nom"
               placeholder="Dupont"
               value="<?= htmlspecialchars($values['nom']) ?>"
               required>
    </div>

    <!-- Champ Email -->
    <div class="form-group">
        <label for="email">Email *</label>
        <!-- type="email" active le clavier email sur mobile -->
        <input type="email" id="email" name="email"
               placeholder="vous@exemple.fr"
               value="<?= htmlspecialchars($values['email']) ?>"
               required>
    </div>

    <!-- Champ Sujet -->
    <div class="form-group">
        <label for="sujet">Sujet *</label>
        <input type="text" id="sujet" name="sujet"
               placeholder="Objet de votre message"
               value="<?= htmlspecialchars($values['sujet']) ?>"
               required>
    </div>

    <!-- Zone de texte pour le message -->
    <div class="form-group">
        <label for="message">Message *</label>
        <!-- htmlspecialchars() protège le contenu du textarea contre le XSS -->
        <textarea id="message" name="message"
                  placeholder="Décrivez votre demande en détail…"
                  required><?= htmlspecialchars($values['message']) ?></textarea>
    </div>

    <!-- Bouton de soumission du formulaire -->
    <button type="submit">Envoyer le message</button>

</form>

<ul> <li><a href="index.php">Accueil</a></li></ul>

         
