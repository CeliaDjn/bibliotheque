<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$utilisateur_id = $_SESSION['utilisateur_id'];

if (isset($_GET['titre'])) {
    $titre_livre_enc = $_GET['titre'];
    $titre_livre = urldecode($titre_livre_enc);

    require_once 'connexion_bdd.php';

    $query_get_qte = "SELECT qte FROM emprunts WHERE titre = ? AND nom_util = ?";
    $stmt_get_qte = mysqli_prepare($db, $query_get_qte);

    if ($stmt_get_qte === false) {
        die('Erreur lors de la préparation de la requête SELECT: ' . htmlspecialchars(mysqli_error($db)));
    }

    mysqli_stmt_bind_param($stmt_get_qte, 'ss', $titre_livre, $utilisateur_id);
    mysqli_stmt_execute($stmt_get_qte);
    mysqli_stmt_bind_result($stmt_get_qte, $qte);
    mysqli_stmt_fetch($stmt_get_qte);
    mysqli_stmt_close($stmt_get_qte);

    if ($qte !== null) {
        $query_increment_stock = "UPDATE livres SET stock = stock + ? WHERE titre = ?";
        $stmt_increment = mysqli_prepare($db, $query_increment_stock);

        if ($stmt_increment === false) {
            die('Erreur lors de la préparation de la requête UPDATE: ' . htmlspecialchars(mysqli_error($db)));
        }

        mysqli_stmt_bind_param($stmt_increment, 'is', $qte, $titre_livre);
        $result_increment_stock = mysqli_stmt_execute($stmt_increment);
        mysqli_stmt_close($stmt_increment);

        if ($result_increment_stock) {
            $query_delete_emprunt = "DELETE FROM emprunts WHERE titre = ? AND nom_util = ?";
            $stmt_delete = mysqli_prepare($db, $query_delete_emprunt);

            if ($stmt_delete === false) {
                die('Erreur lors de la préparation de la requête DELETE: ' . htmlspecialchars(mysqli_error($db)));
            }

            mysqli_stmt_bind_param($stmt_delete, 'ss', $titre_livre, $utilisateur_id);
            $result_delete_emprunt = mysqli_stmt_execute($stmt_delete);

            if ($result_delete_emprunt) {
                mysqli_stmt_close($stmt_delete);
                header("Location: user_page.php");
                exit();
            } else {
                echo "Erreur lors de la suppression de l'emprunt: " . htmlspecialchars(mysqli_stmt_error($stmt_delete));
                mysqli_stmt_close($stmt_delete);
            }
        } else {
            echo "Erreur lors de l'incrémentation du stock du livre: " . htmlspecialchars(mysqli_stmt_error($stmt_increment));
        }
    } else {
        echo "Erreur: Quantité empruntée non trouvée.";
    }
} else {
    echo "Titre du livre non spécifié.";
}
?>
