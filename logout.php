<?php
session_start();
unset($_SESSION['user']);
unset($_SESSION['login-button']);
header('Location: entrance.php');
