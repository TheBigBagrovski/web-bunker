<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: entrance.php');
}
require_once('header.php') ?>

<div class="outer" id="profile-outer">
    <div class="container">
        <div class="inner" id="profile-inner">
            <form action="logout.php">
                <h4 id="username">Пользователь: <?= $_SESSION['user']['login'] ?></h4>
                <button type="submit" class="button" id="exit-button">ВЫХОД</button>
            </form>
        </div>
    </div>
</div>

<?php require_once('footer.php') ?>