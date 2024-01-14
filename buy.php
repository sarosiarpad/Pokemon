<?php
require 'storage.php';

session_start();
$userId = $_SESSION['userId'];
$storage = $_SESSION['storage'];

if (isset($_GET['cardId'])) {
    $cardId = $_GET['cardId'];

    // Betöltjük a kiválasztott kártya részleteit
    $cardDetails = $storage->getCardById($cardId);

    // Ellenőrizzük, hogy a kártya létezik-e
    if ($cardDetails) {
        // Ellenőrizzük, hogy a felhasználó be van jelentkezve
        if ($userId) {
            $message = $storage->buyCard($userId, $cardId);
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
