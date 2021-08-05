<?php 

$user = 'admin';
    $password = '7f66JZuDKykJ16';
    $db = 'lobbies';
    $host = 'localhost';
    $dblobbies = mysqli_connect($host, $user, $password, $db);
    $dblobbies->query("SET NAMES 'utf8'");

if(!$dblobbies) {
    die('connection failed');
}
