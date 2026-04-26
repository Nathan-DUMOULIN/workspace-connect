
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/nav.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <title>NM WORKSPACE</title>
    
</head>
<body class="home">

<header>
    <h1>NM WORKSPACE</h1>
    
</header>

<nav class="navbar">
    <ul>
        <li><a href="index.php" class="active">Accueil</a></li>
        <li><a href="salles.php">Nos salles</a></li>
        <li><a href="reservation.php">Réserver</a></li>
        <li><a href="contact.php">Contact</a></li>
    </ul>
</nav>

<div class="container">
    <main id="salles">

        <h2>Nos salles disponibles</h2>

        <div id="accueil-salles">

            <!-- Salle 1 -->
            <article class="salle-card-home">
                <img src="images/salle1.jpg" alt="Salle Horizon">

                <div class="content">
                    <h3>Salle Horizon</h3>
                    <p>Capacité : 50 personnes | Prix : 120€ / Heure</p>
                    <p>Salle moderne idéale pour réunions et séminaires.</p>

                    <a href="reservation.php" class="btn-discover">
                        Découvrir notre salle
                    </a>
                </div>
            </article>

            <!-- Salle 2 -->
            <article class="salle-card-home">
                <img src="images/salle4.jpg" alt="Salle Lumière">

                <div class="content">
                    <h3>Salle Lumière</h3>
                    <p>Capacité : 80 personnes | Prix : 180€ / Heure</p>
                    <p>Grande salle lumineuse parfaite pour conférences.</p>

                    <a href="reservation.php" class="btn-discover">
                        Découvrir notre salle
                    </a>
                </div>
            </article>

        </div>

    </main>
</div>

    


<footer>
    <p>© WORKSPACE CONNECT</p>
</footer>

</body>
</html>
