<?php
session_start();
if ($_SESSION['user']) {
    header('Location: profile.php');
}
require_once('header.php')
?>

<div class="outer" id="register-outer">
    <div class="container">
        <div class="inner" id="register-inner">
            <form action="register-backend.php" method="post" class="inner-form">
                <h2 id="register-heading">РЕГИСТРАЦИЯ</h2>
                E-Mail:
                <br>
                <input type="text" maxlength="100" class="inputfield register-input" name="mail" placeholder="E-Mail">
                <br> Придумайте логин:
                <br>
                <input type="text" class="inputfield  register-input" name="login" placeholder="Введите логин">
                <br> Придумайте пароль:
                <br>
                <input type="password" class="inputfield  register-input" name="first-password" placeholder="Введите пароль"> Повторите пароль:
                <br>
                <input type="password" class="inputfield  register-input" name="second-password" placeholder="Повторите пароль">
                <button type="submit" class="button" id="register-button">Создать аккаунт</button>
                <?php
                if ($_SESSION['message']) {
                    echo '<p id="error-message"> ' . $_SESSION['message'] . ' </p>';
                    echo '<style type="text/css">
                    #register-inner{ height:480px; }
                    </style>';
                }
                unset($_SESSION['message']);
                ?>
            </form>
        </div>
    </div>
</div>

<?php require_once('footer.php') ?>