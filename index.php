<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation de Salles</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>

<header>
    <h1>Réservation de Salles</h1>
    <p>Trouvez la salle idéale pour vos événements</p>
</header>

<nav>
    <ul>
        <li><a href="index.php">Accueil</a></li>
        <li><a href="salles.php">Nos salles</a></li>
        <li><a href="reservation.php">Réserver</a></li>
        <li><a href="#">Contact</a></li>
    </ul>
</nav>

<div class="container">
    <main id="salles">
        <h2>Nos salles disponibles</h2>

        <article class="salle-card">
            <div class="salle-image">
                <img src="images/salle1.jpg" alt="Salle Horizon">
            </div>
            <div class="salle-content">
                <div class="tags">
                    <span class="tag">Moderne</span>
                </div>
                <h3>Salle Horizon</h3>
                <p class="info">Capacité : 50 personnes | Prix : 120€ / jour</p>
                <p class="description">Salle moderne idéale pour réunions et séminaires.</p>
                
                <div class="footer-card">
                    <a href="reservation.php" class="btn-discover">Découvrir ce lieu</a>
                </div>
            </div>
        </article>

        <article class="salle-card">
            <div class="salle-image">
                <img src="images/salle2.jpg" alt="Salle Lumière">
            </div>
            <div class="salle-content">
                <div class="tags">
                    <span class="tag">Lumineux</span>
                </div>
                <h3>Salle Lumière</h3>
                <p class="info">Capacité : 80 personnes | Prix : 180€ / jour</p>
                <p class="description">Grande salle lumineuse parfaite pour conférences.</p>
                
                <div class="footer-card">
                    <a href="reservation.php" class="btn-discover">Découvrir ce lieu</a>
                </div>
            </div>
        </article>
    </main>

    <aside class="sidebar">
        <div class="filter-box">
            <h3>Filtrer les lieux</h3>
            <form action="index.php" method="GET">
                <input type="number" name="participants" placeholder="Nombre de Participants">
                <select name="type">
                    <option value="">Type de lieu</option>
                    <option value="hotel">Hôtel</option>
                    <option value="chateau">Château</option>
                </select>
                <button type="submit" class="btn-search">Rechercher</button>
            </form>
        </div>
    </aside>
</div>

<footer>
    <p>© 2026 - Réservation de Salles</p>
</footer>

</body>
</html>
