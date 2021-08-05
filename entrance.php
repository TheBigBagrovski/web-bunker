<?php
session_start();
if ($_SESSION['user']) {
    header('Location: profile.php');
}
require_once('header.php')
?>

<div class="outer" id="entrance-outer">
    <div class="container">
        <div class="inner" id="entrance-inner">
            <form action="entrance-backend.php" method="POST" class="inner-form">
                <h1 id="entrance-heading">Вход</h1>
                Логин:
                <br>
                <input type="text" name="login" class="inputfield entrance-input" id="entrance-inputfield-login" placeholder="Введите логин">
                <br> Пароль:
                <br>
                <input type="password" name="password" class="inputfield entrance-input" placeholder="Введите пароль">
                <a href="password_reset.php" id="forgot-password">Забыли пароль?</a>
                <br>
                <button class="button" type="submit" id="button-signin">ВОЙТИ</button>
                <?php
                if ($_SESSION['message']) {
                    echo '<p id="error-message"> ' . $_SESSION['message'] . ' </p>';
                    echo '<style type="text/css">
                    #entrance-inner{ height:370px; }
                    </style>';
                }
                unset($_SESSION['message']);
                ?>
            </form>
        </div>
    </div>
</div>

<?php require_once('footer.php') ?>