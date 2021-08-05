<?php
session_start();

require_once('header.php') ?>

<div class="outer" id="passwordreset">
    <div class="container">
        <div class="inner" id="passwordreset-inner">
            <form action="password-reset-backend.php" method="post" class="inner-form">
                <h3>Восстановление пароля</h3>
                <p>Введите E-Mail, который Вы использовали при регистрации, на него придет письмо для изменения пароля.</p>
                <input name="mail" type="text" class="inputfield" id="passwordreset-inputfield" placeholder="E-Mail">
                <button type="submit" class="button" id="passwordreset-button">Отправить</button>
                <?php
                if ($_SESSION['message']) {
                    echo '<p id="error-message"> ' . $_SESSION['message'] . ' </p>';
                    echo '<style type="text/css">
                    #passwordreset-inner{ height:330px; }
                    </style>';
                }
                unset($_SESSION['message']);
                ?>
            </form>
        </div>
    </div>
</div>

<?php require_once('footer.php') ?>