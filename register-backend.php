<?php
session_start();
require_once('register-db-connect.php');

$address_site = 'https://bunker56game.sytes.net';
$mail_admin = 'admin@bunker56game.sytes.net';
$uniqueCheckQlogin = 'SELECT COUNT(*) FROM `users` WHERE `login` = \'' .  $_POST['login'] . '\' LIMIT 1';
$checkResultlogin = mysqli_fetch_row(mysqli_query($dbregister, $uniqueCheckQlogin));
$uniqueCheckQmail = 'SELECT COUNT(*) FROM `users` WHERE `mail` = \'' .  $_POST['mail'] . '\' LIMIT 1';
$checkResultmail = mysqli_fetch_row(mysqli_query($dbregister, $uniqueCheckQmail));

if ($checkResultlogin[0] > 0) {
    $error = 'Логин занят';
}
if ($checkResultmail[0] > 0) {
    $error = 'На этот почтовый ящик уже создан аккаунт';
}
if ($_POST['first-password'] !== $_POST['second-password']) {
    $error = 'Пароли не совпадают';
}
if (mb_strlen($_POST['login']) < 3 || mb_strlen($_POST['login']) > 90) {
    $error = 'Недопустимый размер логина (3-90 символов)';
}
if (mb_strlen($_POST['first-password']) < 3 || mb_strlen($_POST['first-password']) > 20) {
    $error = 'Недопустимый размер пароля (3-20 символов)';
}
if (!$_POST['second-password']) {
    $error = 'Введите повторный пароль';
}
if (!$_POST['first-password']) {
    $error = 'Введите пароль';
}
if (!$_POST['login']) {
    $error = 'Введите логин';
}
if (!$_POST['mail']) {
    $error = 'Введите E-Mail';
}
if (!$error) {
    $mail = $_POST['mail'];
    $login = $_POST['login'];
    $pass = password_hash($_POST['first-password'], PASSWORD_DEFAULT);

    // удаление просроченных пользователей из users
    $query_delete_users = $dbregister->query("DELETE FROM `users` WHERE `mail_confirm` = 0 AND `register_date` < ( NOW() - INTERVAL 1 DAY )");
    if (!$query_delete_users) {
        die("Сбой при удалении просроченного аккаунта.");
    }
    //удаление просроченных пользователей из users-to-confirm
    $query_delete_confirm_users = $dbregister->query("DELETE FROM `users-to-confirm` WHERE `register_date` < ( NOW() - INTERVAL 1 DAY)");
    if (!$query_delete_confirm_users) {
        die("Сбой при удалении просроченного аккаунта(confirm)");
    }

    $dbregister->query("INSERT INTO `users` (`mail`, `login`, `password`, `mail_confirm`, `register_date`) VALUES ('$mail','$login','$pass', '0', NOW())");
    $token = md5($mail . time());
    $query_insert_confirm = $dbregister->query("INSERT INTO `users-to-confirm` (mail, token, register_date) VALUES ('$mail','$token', NOW())");
    if (!$query_insert_confirm) {
        die("Ошибка при отправке данных в confirm");
    } else {
        $subject = "Подтвердите почту на сайте " . $_SERVER['HTTP_HOST'];
        $subject = "=?utf-8?B?" . base64_encode($subject) . "?=";
        $message = 'Сегодня ' . date("d.m.Y", time()) . ', используя этот почтовый ящик, была произведена регистрация на сайте <a href="' . $address_site . '"></a>' . $_SERVER['HTTP_HOST'] .
            '. Для завершения регистрации перейдите по этой ссылке: <a href="' . $address_site . '/activation.php?token=' . $token . '&mail=' . $mail . '">' . $address_site . "/activation" . "/" . $token . '</a><br><br>Если это были не Вы, проигнорируйте это письмо.<br><br>
        Ссылка действительна 24 часа.';
        $headers = "FROM: $mail_admin\r\nReply-to: $mail_admin\r\nContent-type: text/html; charset=utf-8\r\n";
        if (mail($mail, $subject, $message, $headers)) {
            $_SESSION['message'] = 'Регистрация успешна, мы отправили письмо с подтверждением Вам на почту';
            header('Location: entrance.php');
        } else {
            die("Ошибка при отправлении письма с ссылкой подтверждения, на указанную почту $mail");
        }
    }
    $query_insert_confirm->close();
} else {
    $_SESSION['message'] = $error;
    header('Location: register.php');
}

$dbregister->close();
