<?php
require 'storage.php';

session_start();
$current_user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
$storage = $_SESSION['storage'];

if (isset($_GET['cardId'])) {
    $cardId = $_GET['cardId'];

    // Betöltjük a kiválasztott kártya részleteit
    $cardDetails = $storage->getCardById($cardId);

    // Ellenőrizzük, hogy a kártya létezik-e
    if ($cardDetails) {
        // Ellenőrizzük, hogy a felhasználó be van jelentkezve
        if ($current_user) {
            $message = $storage->buyCard($current_user['username'], $cardId);
            echo $message;
        } else {
            echo "Nincs bejelentkezve senki.";
        }
    } else {
        echo "A kiválasztott kártya nem található.";
    }
} else {
    echo "Nincs kiválasztott kártya.";
}
?>
