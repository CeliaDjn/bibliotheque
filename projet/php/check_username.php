<?php
     require_once 'connexion_bdd.php';

$username = $_POST['username'];

$sql = "SELECT * FROM utilisateur WHERE nom_util = ?";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    echo "Ce nom d'utilisateur est déjà utilisé.";
} else {
    echo "Nom d'utilisateur disponible.";
}

mysqli_stmt_close($stmt);
mysqli_close($db);
?>
