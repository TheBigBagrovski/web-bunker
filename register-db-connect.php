<?php

$user = 'admin';
$password = '7f66JZuDKykJ16';
$db = 'register-db';
$host = 'localhost';
$dbregister = mysqli_connect($host, $user, $password, $db);

if (!$dbregister) {
    die('register-db connection failed');
}
