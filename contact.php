<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/nav.css">
  <title>Nos salles</title>
  
<nav class="navbar">
    <ul>
        <li><a href="index.php">Accueil</a></li>
        <li><a href="salles.php">Nos salles</a></li>
        <li><a href="reservation.php">Réserver</a></li>
        <li><a href="contact.php" class="active">Contact</a></li>
    </ul>
</nav>
<?php

require_once 'config/database.php';
n
$errors = [];


$values = [
    'nom'     => '',
    'email'   => '',
    'sujet'   => '',
    'message' => '',
];


$success = false;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $values['nom']     = trim($_POST['nom']     ?? '');

   
    $values['email']   = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);

    $values['sujet']   = trim($_POST['sujet']   ?? '');

    $values['message'] = trim($_POST['message'] ?? '');


    if (strlen($values['nom']) < 2)
        $errors[] = "Le nom doit contenir au moins 2 caractères.";

  
    if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL))
        $errors[] = "L'adresse email est invalide.";

   
    if (empty($values['sujet']))
        $errors[] = "Le sujet est obligatoire.";

 
    if (strlen($values['message']) < 10)
        $errors[] = "Le message doit contenir au moins 10 caractères.";


    if (empty($errors)) {
        try {
            $sql = 'INSERT INTO contact (nom, email, sujet, message, date_envoi)
                    VALUES (?, ?, ?, ?, NOW())';

  
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $values['nom'],     
                $values['email'],   
                $values['sujet'],   
                $values['message'], 
                
            ]);

          
            $values = ['nom' => '', 'email' => '', 'sujet' => '', 'message' => ''];

            $success = true;

        } catch (PDOException $e) {
           
            $errors[] = "Erreur lors de l'envoi : " . $e->getMessage();
        }
    }
}
?>

<?php if ($success) : ?>
    <div class="alert alert-success">

        <p><strong>Message envoyé !</strong> Merci, nous vous répondrons dans les plus brefs délais.</p>
    </div>
<?php endif; ?>

<?php if (!empty($errors)) : ?>
    <div class="alert alert-error">
        <p><strong>Veuillez corriger les erreurs suivantes :</strong></p>
        <ul>
            <?php foreach ($errors as $err) : ?>
                
                <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>


<form method="POST" action="contact.php" novalidate>

    <div class="form-group">
        <label for="nom">Nom *</label>
        
        <input type="text" id="nom" name="nom"
               placeholder="Dupont"
               value="<?= htmlspecialchars($values['nom']) ?>"
               required>
    </div>

    <div class="form-group">
        <label for="email">Email *</label>>
        <input type="email" id="email" name="email"
               placeholder="vous@exemple.fr"
               value="<?= htmlspecialchars($values['email']) ?>"
               required>
    </div>

    <div class="form-group">
        <label for="sujet">Sujet *</label>
        <input type="text" id="sujet" name="sujet"
               placeholder="Objet de votre message"
               value="<?= htmlspecialchars($values['sujet']) ?>"
               required>
    </div>

    <div class="form-group">
        <label for="message">Message *</label>

        <textarea id="message" name="message"
                  placeholder="Décrivez votre demande en détail…"
                  required><?= htmlspecialchars($values['message']) ?></textarea>
    </div>

    <input type="submit" class="btn-form" value="Envoyer le message">

</form>

         
