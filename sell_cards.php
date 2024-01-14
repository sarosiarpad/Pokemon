<?php
require 'storage.php';

session_start();
$current_user = $_SESSION['user'];
$storage = $_SESSION['storage'];

function generateSellPage($current_user, $storage){
    $cards = $storage->getUserCards($current_user);
    $html = "";

    foreach($cards as $cardId => $card){
        $html .= generateCardHTML($cardId, $card, $current_user, $storage);
    }
    return $html;
}

function generateCardHTML($cardId, $card, $current_user, $storage){
    $price = (int)$card['price'] / 90;

    $sellButton = "<a href='sell.php?cardId=$cardId'><span class='card-buy'><span class='icon'>💰</span>Eladási ár: {$price}</span></a>";

    return "
        <div class='pokemon-card'>
            <div class='image clr-normal'>
                <div class='image clr-{$card['type']}'>
                    <a href='details.php?id=$cardId'>
                        <img src='{$card['image']}' alt='{$card['name']} Kártya'>
                    </a>    
                </div>
            </div>
            <div class='details'>
                <h2><a href='details.php?id=$cardId'>$card[name]</a></h2>
                <span class='card-type'><span class='icon'>🏷</span> electric</span>
                <span class='attributes'>
                    <span class='card-hp'><span class=~icon~>❤</span>$card[hp]</span>
                    <span class=~card-attack~><span class=~icon~>⚔</span>$card[attack]</span>
                    <span class=~card-defense~><span class=~icon~>🛡</span>$card[defense]</span>
                </span>
            </div>
            $sellButton
        </div>
    ";
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kártyák eladása</title>
</head>
<body>
    <header>
        <h1>Felhasználó részletek</h1>
        <nav>
            <ul>
                <li><a href='index.php'>Főoldal</a></li>
                <li><a href='my_cards.php'>Kártyáim</a></li>
                <li><a href='logout.php'>Kijelentkezés</a></li>
            </ul>
        </nav>
    </header>
    <div id="content">
        <?php echo generateSellPage($current_user, $storage); ?>
    </div>
</body>
</html>