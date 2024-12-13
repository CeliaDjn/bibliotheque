<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

function renouvelerDateRendu($titre_livre, $utilisateur_id) {
    require_once 'connexion_bdd.php';

    $nouvelle_date_rendu = date('Y-m-d', strtotime('+2 weeks'));

    $query_update_date_rendu = "UPDATE emprunts SET date_rendu = ? WHERE titre = ? AND nom_util = ?";
    $stmt = mysqli_prepare($db, $query_update_date_rendu);

    if ($stmt === false) {
        die('Erreur lors de la préparation de la requête SQL: ' . htmlspecialchars(mysqli_error($db)));
    }

    mysqli_stmt_bind_param($stmt, 'sss', $nouvelle_date_rendu, $titre_livre, $utilisateur_id);

    $result_update_date_rendu = mysqli_stmt_execute($stmt);

    if (!$result_update_date_rendu) {
        return "Erreur lors de la mise à jour de la date de rendu du livre.";
    }

    mysqli_stmt_close($stmt);

    return true;
    }

if (isset($_GET['titre'])) {
    $titre_livre = $_GET['titre'];
    $utilisateur_id = $_SESSION['utilisateur_id'];

    $resultat = renouvelerDateRendu($titre_livre, $utilisateur_id);

    if ($resultat === true) {
        header("Location: user_page.php");
        exit();
    } else {
        echo $resultat;
    }
} else {
    echo "Titre du livre non spécifié.";
}
?>
