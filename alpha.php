<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Работает</title>
</head>
<body>
<?php
    $user = 'admin';
    $password = '7f66JZuDKykJ16';
    $db = 'lobbies';
    $host = 'localhost';

    $dblobbies = new mysqli($host, $user, $password, $db);
    $dblobbies->query("SET NAMES 'utf8'");
    require_once "bunker.php";
    $game = new bunker();
    if((($_POST['nickname1']=="") || ($_POST['plnumber']=="")) && isset( $_POST['Кнопка1'])&&!(isset( $_POST['Кнопка3'])))
    {
        $button1 = true;
        $_POST['Кнопка1'] = NULL;
    }
    if( ( ($_POST['nickname2']=="") || ($_POST['lbnumber']=="") ) && isset( $_POST['Кнопка3'])&&!(isset( $_POST['Кнопка1'])))
    {
        $button3 = true;
        $_POST['Кнопка3'] = NULL;
    }
    if( isset( $_POST['Кнопка1'] )&&!(isset( $_POST['Кнопка3'])) && ($_POST['nickname1']!="") && ($_POST['plnumber']!="") )
    {
        setcookie("lbname", $game->createlobby($dblobbies,$_POST['nickname1'],
                    $_POST['plnumber'],$_POST['password']),time()+5400);
    }
    if( isset( $_POST['Кнопка2'] ) )
    {
        $game->end($dblobbies, $_COOKIE['lbname']);
        $_POST['Кнопка2']=NULL;
    }
    if( isset($_POST['Кнопка3']) && !( isset($_POST['Кнопка1']) ) && ($_POST['nickname2']!="") ):
        echo "Начало подключения к лобби<br>";
        setcookie("nickname",$_POST['nickname2'],time()+7200);
        setcookie("lbnumber",$_POST['lbnumber'],time()+7200);
        $game->connectToLobby($dblobbies,$_POST['nickname2'],$_POST['lbnumber'],$_POST['password']);
        echo "Конец подключения к лобби<br>";
?>
<form method="POST">
    <input type="submit" name="Кнопка4" value="Покинуть лобби" />
</form>
<?php
    endif;
    if( isset($_POST['Кнопка4']) )
        $game->leaveLobby($dblobbies, $_COOKIE['nickname'], $_COOKIE['lbnumber']);
    if( !(isset( $_POST['Кнопка1'] )) && !(isset( $_POST['Кнопка2'] )) && !(isset( $_POST['Кнопка3'] ) ) && !(isset( $_POST['Кнопка5'] ) ) ):
?>
<form method="POST">
<p>
    <input type="text" name="nickname1">
<?php
    if(($_POST['nickname1']=="") && $button1)
        echo "<br>Введите никнейм<br>";
?>
</p>
<p>
    <select name="plnumber">
        <option value="">Select...</option>
        <option value="4">4 players</option>
        <option value="5">5 players</option>
        <option value="6">6 players</option>
        <option value="7">7 players</option>
        <option value="8">8 players</option>
        <option value="9">9 players</option>
        <option value="10">10 players</option>
    </select>
<?php
    if(($_POST['plnumber']=="") && $button1)
        echo "<br>Выберите значение<br>";
?>
</p>
<p>
    <input type="text" name="password">
</p>
    <input type="submit" name="Кнопка1" value="Создать лобби" />
</form>
<form method="POST">
<p>
    <input type="text" name="nickname2">
<?php
    if(($_POST['nickname2']=="") && $button3)
        echo "<br>Введите никнейм<br>";
?>
</p>
<p>
    <input type="text" name="lbnumber">
<?php
    if(($_POST['lbnumber']=="") && $button3)
        echo "<br>Введите номер лобби<br>";
?>
</p>
<p>
    <input type="text" name="password">
</p>
    <input type="submit" name="Кнопка3" value="Подключиться к лобби" />
</form>
<?php
    endif;
    if( isset( $_POST['Кнопка5'] ) ):
?>
<form method="POST">
    <input type="submit" name="Кнопка2" value="Закончить игру" />
</form>
<?php
    $game->start($dblobbies,$_COOKIE['lbname']);
    endif;
if(!(isset( $_POST['Кнопка2'] )) && isset($_POST['Кнопка1']) 
        && ($_POST['nickname1']!="") && ($_POST['plnumber']!="")):
?>
<form method="POST">
    <input type="submit" name="Кнопка5" value="Начать игру" />
</form>
<?php
    endif;
    echo "<br>Кнопка 1 = ".isset( $_POST['Кнопка1'] );
    echo "<br>Кнопка 2 = ".isset( $_POST['Кнопка2'] );
    echo "<br>Кнопка 3 = ".isset( $_POST['Кнопка3'] );
    echo "<br>Кнопка 4 = ".isset( $_POST['Кнопка4'] );
    echo "<br>Кнопка 5 = ".isset( $_POST['Кнопка5'] );
    echo "<br>Имя лобби: ".$_COOKIE['lbname']."<br>";
    $dblobbies->close();
?>
</body>
</html>