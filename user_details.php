<?php
require 'storage.php';

session_start();
$current_user = $_SESSION['user'];
$storage = $_SESSION['storage'];

function generateUserDetailsPage($current_user, $storage)
{
    $userDetails = "
        <h2>Felhasználó részletek</h2>
        <p>Felhasználónév: {$current_user['username']}</p>
        <p>Pénz: {$current_user['money']}</p>
    ";

    $sellLink = "<a href='sell_cards.php'>Kártyáim eladása</a>";

    return $userDetails . $sellLink;
}
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Felhasználó részletek</title>
    <link rel="stylesheet" href="styles/main.css">
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
        <?php echo generateUserDetailsPage($current_user, $storage); ?>
    </div>
</body>

</html>