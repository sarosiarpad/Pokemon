<?php
session_start();
unset($_SESSION['user']);
header("Location: index.php");
session_destroy();
exit();
?>
