<?php

session_start();
require_once('lobbies-db-connect.php');
require_once('register-db-connect.php');
require_once("bunker.php");
$game = new bunker();

if (isset($_POST['checkbox']) == true) {
    $password = $_POST['create_password'];
} else {
    $password = "default";
}

if (!$_POST['create_password'] && (isset($_POST['checkbox']) == true)) {
    $error = "Придумайте пароль для лобби";
}
if ($_POST['players-number-select'] == 'Select') {
    $error = "Выберите число игроков";
}
if (!$_POST['nick2']) {
    $error = "Введите Ваш никнейм";
}

$find_free_lobby = mysqli_query($dblobbies, "SELECT * FROM `info` WHERE `free` = 0");
if (mysqli_num_rows($find_free_lobby) == 0) {
    $error = "Нет свободных лобби";
}

$user = $_SESSION['user'];
$playerLogin = $user['login'];
$checkIsNowInLobby = mysqli_query($dbregister, "SELECT * FROM `users` WHERE `login` = '$playerLogin'");
$user = mysqli_fetch_assoc($checkIsNowInLobby);
if ($user['isNowInLobby'] == 1) {
    $error = "Вы уже находитесь в лобби";
}

if (!$error) {
    $setIsNowInLobby = $dbregister->query("UPDATE `users` SET `isNowInLobby` = 1 WHERE `login` = '$playerLogin'");
    setcookie("lbname", $game->createlobby(
        $dblobbies,
        $_POST['nick2'],
        $_POST['players-number-select'],
        $password
    ), time() + 5400);
    header('Location: lobby.php');
} else {
    $_SESSION['lobby-message2'] = $error;
    header('Location: creating_lobby.php');
}

$find_free_lobby->close();
$checkIsNowInLobby->close();
$setIsNowInLobby->close();
