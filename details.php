<?php
require 'storage.php';

session_start();
$current_user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
$storage = $_SESSION['storage'];

// Ellenőrizzük, hogy van-e kiválasztott kártya az "id" paraméterrel
if (isset($_GET['id'])) {
    $cardId = $_GET['id'];

    $cardDetails = $storage->getCardById($cardId);
    // Ellenőrizzük, hogy a kártya létezik-e
    if ($cardDetails) {
        // Itt megjelenítheted a kártya részleteit
        $cardName = $cardDetails['name'];
        $cardImage = $cardDetails['image'];
        $cardType = $cardDetails['type'];
        $cardHp = $cardDetails['hp'];
        $cardAttack = $cardDetails['attack'];
        $cardDefense = $cardDetails['defense'];
        $cardDescription = $cardDetails['description'];
        $cardPrice = $cardDetails['price'];
    } else {
        echo "A kiválasztott kártya nem található.";
        header("Location: index.php");
        exit();
    }
} else {
    echo "Nincs kiválasztott kártya.";
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokémon Kártya Részletek</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/details.css">
</head>
<body>
<header>
    <h1>Pokémon Shop</h1>
    <nav>
    <?php
        if ($current_user) {
            // Be van jelentkezve valaki
            echo "<ul>
                      <li><a href='index.php'>Főoldal</a></li>
                      <li><a>$current_user[username]</a></li>
                      <li><a>$current_user[money]</a></li>
                      <li><a href='my_cards.php'>Kártyáim</a></li>
                      <li><a href='logout.php'>Kijelentkezés</a></li>
                  </ul>";
        } else {
            // Nincs bejelentkezve senki
            echo "<ul>
                      <li><a href='index.php'>Főoldal</a></li>
                      <li><a href='register.php'>Regisztráció</a></li>
                      <li><a href='login.php'>Bejelentkezés</a></li>
                  </ul>";
        }
        ?>
    </nav>
</header>

<div id="content">
    <div id="details">
        <?php
        echo "
        <div class='image clr-{$cardType}'>
            <img src=$cardImage alt=$cardName>
        </div>
        <div class='info'>
            <div class='description'>
                $cardDescription
            </div>
            <span class='card-type'><span class='icon'>🏷</span> Type: $cardType</span>
            <div class='attributes'>
                <div class='card-hp'><span class='icon'>❤</span> $cardHp</div>
                <div class='card-attack'><span class='icon'>⚔</span> Attack: $cardAttack</div>
                <div class='card-defense'><span class='icon'>🛡</span> Defense: $cardDefense</div>
            </div>
        </div>
        " ?>
    </div>
</div>

</body>
</html>
