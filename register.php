<?php
require 'storage.php';

session_start();
$storage = $_SESSION['storage'];

// Űrlap beküldésének ellenőrzése
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Regisztrációs adatok ellenőrzése és feldolgozása
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Ellenőrzés: minden mező kitöltve van?
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        echo "Minden mező kitöltése kötelező!";
        exit();
    }

    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Nem megfelelő email formátum!";
    }

    // Ellenőrzés: a jelszavak egyeznek?
    if ($password !== $confirmPassword) {
        echo "A jelszavak nem egyeznek!";
        exit();
    }

    // Ellenőrzés: felhasználónév egyedisége
    foreach($storage->getAllUsers() as $user){
        if ($user['username'] == $username) {
            echo "A felhasználónév már foglalt!";
            exit();
        }
    }

    // Felhasználó létrehozása és tárolása
    $user = [
        'username' => $username,
        'email' => $email,
        'password' => $password,
        'money' => 500,
        'admin' => false,
        'cards' => [],
    ];

    $storage->saveUser($user);

    $_SESSION['user'] = $user;
    $_SESSION['userId'] = array_search($user, $storage->getAllUsers());
    $_SESSION['storage'] = $storage;

    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title>
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
    <h2>Regisztráció</h2>

    <form action="register.php" method="post">
        <label for="username">Felhasználónév:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="email">Email cím:</label>
        <input type="text" id="email" name="email" required><br>

        <label for="password">Jelszó:</label>
        <input type="password" id="password" name="password" required><br>

        <label for="confirm_password">Jelszó újra:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br>

        <input type="submit" value="Regisztráció">
    </form>
</section>

</body>
</html>