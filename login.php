<?php
require 'storage.php';

session_start();
$storage = $_SESSION['storage'];

// Űrlap beküldésének ellenőrzése
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Bejelentkezési adatok ellenőrzése és feldolgozása
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ellenőrzés: minden mező kitöltve van?
    if (empty($username) || empty($password)) {
        echo "Minden mező kitöltése kötelező!";
        exit();
    }

    // Ellenőrzés: felhasználónév és jelszó egyezése
    $user = $storage->getUserByUsername($username);
    if (!$user || $user['password'] !== $password) {
        echo "Hibás felhasználónév vagy jelszó!";
        exit();
    }

    // Felhasználó bejelentkeztetése
    $_SESSION['user'] = $user;
    $_SESSION['user_index'] = array_search($user, $storage->getAllUsers());

    // Átirányítás a főoldalra
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/form.css">
</head>
<body>
<header>
    <h1>Pokémon Kártyák Kereskedése</h1>
    <nav>
        <ul>
            <li><a href='index.php'>Főoldal</a></li>
            <li><a href='register.php'>Regisztráció</a></li>
            <li><a href='login.php'>Bejelentkezés</a></li>
        </ul>
    </nav>
</header>

<section class="main-content">
    <h2>Bejelentkezés</h2>

    <form action="login.php" method="post">
        <label for="username">Felhasználónév:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Jelszó:</label>
        <input type="password" id="password" name="password" required><br>

        <input type="submit" value="Bejelentkezés">
    </form>
</section> 

</body>
</html>