<?php
session_start();
require_once 'connexion_bdd.php';

if(isset($_GET['id'])) {
    $notification_id = $_GET['id'];

    $delete_query = "DELETE FROM notifications WHERE id = $notification_id";
    $result = mysqli_query($db, $delete_query);

    if($result) {
        header("Location: admin_page.php");
        exit();
    } else {
        echo "Erreur lors de la suppression de la notification.";
    }
} else {
    echo "ID de la notification non spécifié.";
}
?>
