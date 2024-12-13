<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['utilisateur_id'])) {
    echo "Erreur : Utilisateur non connecté.";
    exit();
}

require_once 'connexion_bdd.php';

$search = isset($_POST["search"]) ? $_POST["search"] : '';

$search = '%' . $search . '%';
$requete = "SELECT * FROM livres WHERE titre LIKE ? OR auteur LIKE ? OR categorie LIKE ?";

$stmt = $db->prepare($requete);
$stmt->bind_param('sss', $search, $search, $search);
$stmt->execute();
$res = $stmt->get_result();

$utilisateur_id = $_SESSION['utilisateur_id'];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/biblio.css">
</head>
<body>
<header>
    <h1>Profil de <?php echo htmlspecialchars($utilisateur_id); ?></h1>
    <div class="topnav">
        <a href="user_page.php">Retour au profil</a>
        <a href="#" id="cart">
            Panier: <span id="cartItems"><?php echo count($_SESSION['cart']); ?></span> articles
        </a>
        <a href="../acceuil.php">Se déconnecter</a>
    </div>
</header>

<div class="container">
    <?php
    while ($etu = $res->fetch_assoc()) {
        $titreEchappe = htmlspecialchars(addslashes($etu['titre']));
        $cheminImage = !empty($etu['chemin_image']) ? '../' . htmlspecialchars($etu['chemin_image']) : '../default_image_path.jpg';
        echo "<div class='livre'>";
        echo "<h3>" . htmlspecialchars($etu['titre']) . "</h3>";
        echo "<img src='$cheminImage' alt='Image de " . htmlspecialchars($etu['titre']) . "'>";
        echo "<button onclick=\"addToCart('$titreEchappe', '$cheminImage')\">Ajouter au panier</button>";
        echo "</div>";
    }
    ?>
</div>

<script>
    let cart = <?php echo json_encode($_SESSION['cart']); ?> || [];

    function addToCart(titre, image) {
        let itemIndex = cart.findIndex(item => item.titre === titre);
        if (itemIndex !== -1) {
            cart[itemIndex].quantity += 1;
        } else {
            cart.push({ titre: titre, image: image, quantity: 1 });
        }
        updateCartDisplay();
    }

    function updateCartDisplay() {
        let cartItemsElement = document.getElementById('cartItems');
        let totalItems = cart.reduce((total, item) => total + item.quantity, 0);
        cartItemsElement.innerText = totalItems;
    }

    function displayCart() {
        let cartData = JSON.stringify(cart);
        window.location.href = "panier.php?cart=" + encodeURIComponent(cartData);
    }

    updateCartDisplay();

    document.getElementById('cart').addEventListener('click', displayCart);
</script>

</body>
</html>
