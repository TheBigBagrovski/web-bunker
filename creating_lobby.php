<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: entrance.php');
}

require_once('header.php') ?>

<script>
    function checkCheckbox(f) {
        if (f.checkbox.checked) f.create_password.disabled = 0
        else f.create_password.disabled = 1
    }
</script>

<div class="outer" id="lobby-outer">
    <div class="container" id="lobby-container">
        <div class="inner lobby-inner" id="lobby-inner1">
            <form method="POST" class="inner-form lobby-form">
                <h3 class="lobby-heading">Поиск лобби по id</h3>
                Придумайте ник:
                <input name="nick1" type="text" class="inputfield lobby-inputfield" placeholder="Ваш ник в игре">
                Введите id лобби:
                <input name="idinput" type="text" class="inputfield lobby-inputfield" id="lobby-findid-input" placeholder="Введите id лобби">
                <input type="submit" name="connect-button" class="button" id="findlobby-button" value="ПОИСК" />

                <?php //вывод сообщения с ошибкой
                if ($_SESSION['lobby-message1']) {
                    echo '<p id="error-message"> ' . $_SESSION['lobby-message1'] . ' </p>';
                    echo '<style type="text/css">
                    #lobby-inner1{ height:330px; }
                    </style>';
                }
                unset($_SESSION['lobby-message1']);
                ?>

                <?php //присоединение к лобби без пароля
                session_start();
                if (isset($_POST['connect-button'])) {
                    require_once('lobbies-db-connect.php');
                    require_once('register-db-connect.php');

                    if (!$_POST['idinput']) {
                        $error = "Введите id";
                    }
                    if (!$_POST['nick1']) {
                        $error = "Введите Ваш никнейм";
                    }
                    $user = $_SESSION['user'];
                    $playerLogin = $user['login'];
                    $checkIsNowInLobby = mysqli_query($dbregister, "SELECT * FROM `users` WHERE `login` = '$playerLogin'");
                    $user = mysqli_fetch_assoc($checkIsNowInLobby);
                    if ($user['isNowInLobby'] == 1) {
                        $error = "Вы уже находитесь в лобби";
                    }
                    if (!$error) {
                        $id = $_POST['idinput'];
                        $nick = $_POST['nick1'];
                        $check_lobby = mysqli_query($dblobbies, "SELECT * FROM `info` WHERE `lbnumber` = '$id'");
                        if (mysqli_num_rows($check_lobby) > 0) {
                            $lobby = mysqli_fetch_assoc($check_lobby);
                            $_SESSION['player-name'] = $nick;
                            $_SESSION['lobby-id'] = $id;
                            if ($lobby['lbpass'] != md5("default")) {
                                echo '<form method="POST">Пароль:
                            <input name="password-input" type="text" class="inputfield lobby-inputfield" placeholder="Введите пароль" name="input_password">
                            <input type="submit" name="lobbypass-button" class="button" id="findlobby-button" value="ПОДТВЕРДИТЬ"/>
                            </form>';
                                echo '<style type="text/css">
                            #lobby-inner1{ height:400px; }
                            </style>';
                            } else {
                                require_once("bunker.php");
                                $game = new bunker();
                                $setIsNowInLobby = $dbregister->query("UPDATE `users` SET `isNowInLobby` = 1 WHERE `login` = '$playerLogin'");
                                setcookie("nickname", $nick, time() + 7200);
                                setcookie("lbnumber", $id, time() + 7200);
                                $game->connectToLobby($dblobbies, $nick, $id, "default");
                                header('Location: lobby.php');
                            }
                        } else {
                            $_SESSION['lobby-message1'] = 'Лобби с таким id не найдено';
                            header('Location: creating_lobby.php');
                        }
                    } else {
                        $_SESSION['lobby-message1'] = $error;
                        header('Location: creating_lobby.php');
                    }
                }
                ?>

                <?php
                if (!isset($_SESSION)) { // если сессия не запущена, то запускаем ее
                    session_start();
                }
                if (isset($_POST['lobbypass-button'])) {
                    require_once('lobbies-db-connect.php');
                    require_once('register-db-connect.php');
                    if (!$_POST['password-input']) {
                        $error = "Введите пароль";
                    }
                    if (!$error) {
                        $id = $_SESSION['lobby-id'];
                        $nick = $_SESSION['player-name'];
                        $pass = $_POST['password-input'];
                        $check_pass = mysqli_query($dblobbies, "SELECT `lbpass` FROM `info` WHERE `lbnumber` = '$id'");
                        if (md5($pass) == $check_pass) {
                            require_once("bunker.php");
                            $game = new bunker();
                            $setIsNowInLobby = $dbregister->query("UPDATE `users` SET `isNowInLobby` = 1 WHERE `login` = '$playerLogin'");
                            setcookie("nickname", $nick, time() + 7200);
                            setcookie("lbnumber", $id, time() + 7200);
                            $game->connectToLobby($dblobbies, $nick, $id, $pass);
                            header('Location: lobby.php');
                        } else {
                            $_SESSION['lobby-message1'] = 'Неверный пароль';
                            header('Location: creating_lobby.php');
                        }
                    } else {
                        $_SESSION['lobby-message1'] = $error;
                        header('Location: creating_lobby.php');
                    }
                }
                ?>
            </form>
        </div>
        <div class="inner lobby-inner" id="lobby-inner2">
            <form action="creating-lobby-backend.php" method="POST" class="inner-form lobby-form">
                <h3 class="lobby-heading">Создание лобби</h3>
                Придумайте ник:
                <input name="nick2" type="text" class="inputfield lobby-inputfield" placeholder="Ваш ник в игре">
                <input type="checkbox" id="with-password" name="checkbox" onclick="checkCheckbox(this.form)">
                <label for="with-password">С паролем</label>
                <input name="create_password" type="text" class="inputfield lobby-inputfield" placeholder="Придумайте пароль" name="create_password" disabled> Количество игроков:
                <select name="players-number-select">
                    <option value="Select">Select</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
                <br>
                <button name="create-button" class="button" id="create-lobby-button">Создать лобби</button>

                <?php
                if ($_SESSION['lobby-message2']) {
                    echo '<p id="error-message"> ' . $_SESSION['lobby-message2'] . ' </p>';
                    echo '<style type="text/css">
                    #lobby-inner2{ height:360px; }
                    </style>';
                }
                unset($_SESSION['lobby-message2']);
                ?>
            </form>
        </div>
    </div>
</div>

<?php require_once('footer.php') ?>