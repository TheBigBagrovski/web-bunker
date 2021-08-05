<?php
session_start();
require_once('register-db-connect.php');
$address_site = 'https://bunker56game.sytes.net';
$mail_admin = 'admin@bunker56game.sytes.net';
$mail = $_POST['mail'];

$check_user = mysqli_query($dbregister, "SELECT * FROM `users` WHERE `mail` = '$mail'");
if (mysqli_num_rows($check_user) > 0) {
    $user = mysqli_fetch_assoc($check_user);

    //генерация нового пароля
    $chars = "qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
    $max = 10;
    $size = StrLen($chars) - 1;
    $newPass = null;
    while ($max--)
        $newPass .= $chars[rand(0, $size)];

    $subject = "Сброс пароля на сайте " . $_SERVER['HTTP_HOST'];
    $subject = "=?utf-8?B?" . base64_encode($subject) . "?=";
    $message = 'Сегодня ' . date("d.m.Y", time()) . ', используя этот почтовый ящик, был отправлен запрос на восстановление пароля на сайте <a href="' . $address_site . '"></a>' . $_SERVER['HTTP_HOST'] .
        '. <br><br>Ваш новый пароль: ' . $newPass . '<br> Используйте его для входа в аккаунт. ';
    $headers = "FROM: $mail_admin\r\nReply-to: $mail_admin\r\nContent-type: text/html; charset=utf-8\r\n";
    if (mail($mail, $subject, $message, $headers)) {
        $_SESSION['message'] = 'Письмо отправлено';
        header('Location: password_reset.php');
        $pass = password_hash($newPass, PASSWORD_DEFAULT);
        $query_update_user = $dbregister->query("UPDATE `users` SET `password` = '" . $pass . "' WHERE `mail` = '" . $mail . "'");
    } else {
        die("Ошибка при отправлении письма с новым паролем, на указанную почту $mail");
    }
} else {
    $_SESSION['message'] = 'На введенный E-Mail аккаунт не зарегистрирован';
    header('Location: password_reset.php');
}
$query_update_user->close();
$dbregister->close();
