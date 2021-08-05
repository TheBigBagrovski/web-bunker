<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Бункер</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
</head>

<body>
    <header id="header">
        <div class="container">
            <div id="header-inner">
                <a href="creating_lobby.php">
                    <img src="assets/images/logo.svg" id="header-logo" height="70px">
                </a>
                <nav id="header-navigation">
                    <a href="index.php" class="header-navigation-link">ПРАВИЛА</a>
                    <a href="creating_lobby.php" class="header-navigation-link">ИГРАТЬ</a>
                    <a href="entrance.php" class="header-navigation-link">
                        <?php if ($_SESSION['login-button'] != 0) {
                            echo $_SESSION['login-button']['text'];
                        } else {
                            echo "ВХОД";
                        } ?></a> |
                    <a href="register.php" class="header-navigation-link">РЕГИСТРАЦИЯ</a>
                </nav>
            </div>
        </div>
    </header>