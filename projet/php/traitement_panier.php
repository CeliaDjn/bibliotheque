<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'connexion_bdd.php';

$utilisateur_id = $_SESSION['utilisateur_id'];

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
    $success = true;
    $errorMessage = '';

    foreach ($cart as $item) {
        $titre = $item['titre'];
        $query = "SELECT * FROM livres WHERE titre = ?";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "s", $titre);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        $qte = $item['quantity'];

        if ($qte > $row['stock']) {
            $success = false;
            $errorMessage .= "Stock insuffisant pour le livre '" . htmlspecialchars($titre) . "'. Quantité disponible : " . $row['stock'] . ". ";
            continue;
        }

        $today = date("Y-m-d");
        $checkQuery = "SELECT qte FROM emprunts WHERE nom_util = ? AND titre = ? AND date_emprunt = ?";
        $checkStmt = mysqli_prepare($db, $checkQuery);
        mysqli_stmt_bind_param($checkStmt, "sss", $utilisateur_id, $titre, $today);
        mysqli_stmt_execute($checkStmt);
        $checkResult = mysqli_stmt_get_result($checkStmt);

        if (mysqli_num_rows($checkResult) > 0) {
            $rowEmprunt = mysqli_fetch_assoc($checkResult);
            $newQteEmprunt = $qte + $rowEmprunt['qte'];
            $updateQuery = "UPDATE emprunts SET qte = ? WHERE nom_util = ? AND titre = ? AND date_emprunt = ?";
            $updateStmt = mysqli_prepare($db, $updateQuery);
            mysqli_stmt_bind_param($updateStmt, "isss", $newQteEmprunt, $utilisateur_id, $titre, $today);
            mysqli_stmt_execute($updateStmt);
        } else {
            $empruntDate = date("Y-m-d");
            $dateRendu = date("Y-m-d", strtotime($empruntDate . "+2 weeks")); // Date de rendu dans 2 semaines
            $insertQuery = "INSERT INTO emprunts (nom_util, titre, date_emprunt, date_rendu, qte) VALUES (?, ?, ?, ?, ?)";
            $insertStmt = mysqli_prepare($db, $insertQuery);
            mysqli_stmt_bind_param($insertStmt, "ssssi", $utilisateur_id, $titre, $empruntDate, $dateRendu, $qte);
            mysqli_stmt_execute($insertStmt);
        }

        $newStock = $row['stock'] - $qte;
        $updateQuery = "UPDATE livres SET stock = ? WHERE titre = ?";
        $updateStmt = mysqli_prepare($db, $updateQuery);
        mysqli_stmt_bind_param($updateStmt, "is", $newStock, $titre);
        mysqli_stmt_execute($updateStmt);
    }

    if ($success) {
        unset($_SESSION['cart']);
        header('Location: user_page.php');
        exit;
    } else {
        header('Location: panier.php?error=' . urlencode($errorMessage));
        exit;
    }
} else {
    header('Location: panier.php');
    exit;
}
mysqli_close($db);
?>
