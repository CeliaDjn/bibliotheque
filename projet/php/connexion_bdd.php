<?php
// Connexion à la base de données
//$db = mysqli_connect('hôte', 'utilisateur', 'mot_de_passe', 'base_de_données');


if (!$db) {
    die("La connexion à la base de données a échoué : " . mysqli_connect_error());
}

mysqli_set_charset($db, "utf8");
?>
