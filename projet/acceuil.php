<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliothèque</title>
    <link rel="stylesheet" href="css/main.css">

</head>
<body>
    <header>
    <div id="mc">

           <a href="php/user_connexion.php">
             <img src="images/t.png" alt="Icône Profil"
             width="10" height="10">
             <strong>Mon compte</strong> </a>
    </div>
    </header>


    <div id="acc">
     <h1>Bienvenue à notre bibliothèque.Empruntez,rendez ou renouvelez !</h1>
     <br>
        <div id="acc1">
     <h2>Notre bibliothèque vous ouvre les portes d'un monde littéraire fascinant. Que vous soyez passionné de romans, de poésie,
       d'essais ou de bandes dessinées, notre collection soigneusement sélectionnée saura vous captiver. Utilisez notre moteur de recherche pour trouver rapidement le livre de vos rêves.
       Triez par genre, auteur ou année de publication, et laissez-vous surprendre par nos suggestions du mois. Connectez-vous pour sauvegarder vos favoris et suivre vos lectures. Bienvenue dans notre univers où chaque page est une nouvelle aventure !</h2>

        <details>
            <summary>Voir plus</summary>
                <p>Dans notre bibliothèque, vous pouvez consulter votre profil, gérer vos emprunts et effectuer des recherches de livres par titre, auteur ou catégorie. Ajoutez vos livres préférés à votre panier, validez votre sélection, et vous recevrez une confirmation détaillée de vos emprunts.</p>
        </details>

        </div>

    </div>



        <section>
        <div id="sec">
            <h1>Selection du mois</h1>
           <div class="container">

            <?php
            error_reporting(E_ALL);
            ini_set('display_errors', 1);

            require_once 'php/connexion_bdd.php';
            $result = $db->query('SELECT * FROM livres ORDER BY stock LIMIT 2');

            while ($livre = mysqli_fetch_assoc($result)) {
                echo '<div class="book">';
                echo '<h2>' . $livre['titre'] . '</h2>';
                echo '<p>Auteur: ' . $livre['auteur'] . '</p>';
                echo '<p>Genre: ' . $livre['categorie'] . '</p>';
                echo '<img src="' . $livre['chemin_image'] . '" alt="Image du livre">';
                echo '</div>';
            }
            $db = null;
            ?>
            </div>
    </div>

        </section>

</body>
</html>
