<?php
require 'storage.php';

session_start();
$current_user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

if (!isset($_SESSION['storage'])) {
    $storage = new Storage();
    $_SESSION['storage'] = $storage;
} else {
    $storage = $_SESSION['storage'];
}

// Főoldal generálása
function generateHomePage($current_user, $storage)
{
    $cards = $storage->getAllCards();
    $html = "";

    foreach ($cards as $cardId => $card) {
        $html .= generateCardHTML($cardId, $card, $current_user, $storage);
    }

    return $html;
}

// HTML kód generálása a Pokémon kártyákhoz
function generateCardHTML($cardId, $card, $current_user, $storage)
{
    $buyButton = '';
    if ($current_user) {
        $admin = $storage->getUserByUsername("admin");
        foreach($admin['cards'] as $adminCard){
            if($cardId == $adminCard){
                $buyButton = "<a href='buy.php?cardId=$cardId'><span class='card-buy'><span class='icon'>💰</span>{$card['price']}</span></a>";
                break;
            }else{
                $buyButton = "Elfogyott";
            }
        }
    }else{
        $buyButton = "A kártya megvásárlásához be kell jelentkezni!";
    }

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
            $buyButton
        </div>
    ";
}
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokémon Kártyák Kereskedése</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
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
                      <li><a href='user_details.php'>{$current_user['username']}</a></li>
                      <li><a>{$current_user['money']}</a></li>
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
        <div id="card-list">
            <?php echo generateHomePage($current_user, $storage); ?>
        </div>
    </div>
</body>

</html>