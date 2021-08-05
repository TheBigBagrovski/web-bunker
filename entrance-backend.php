<?php
session_start();
require_once('register-db-connect.php');

$login = $_POST['login'];
$pass = $_POST['password'];

$check_user = mysqli_query($dbregister, "SELECT * FROM `users` WHERE `login` = '$login'");

if (mysqli_num_rows($check_user) > 0) {
    $user = mysqli_fetch_assoc($check_user);
    if ((int)$user['mail_confirm'] == 1) {
        if (password_verify($pass, $user['password'])) {
            $_SESSION['user'] = [
                "id" => $user['id'],
                "mail" => $user['mail'],
                "login" => $user['login'],
            ];
            $_SESSION['login-button'] = [
                "text" => $user['login'],
                "file" => 'profile.php'
            ];
            header('Location: profile.php');
        } else {
            $_SESSION['message'] = 'Неверный логин или пароль';
            header('Location: entrance.php');
        }
    } else {
        $_SESSION['message'] = 'Вы не подтвердили почтовый ящик';
        header('Location: entrance.php');
    }
} else {
    $_SESSION['message'] = 'Неверный логин или пароль';
    header('Location: entrance.php');
}
