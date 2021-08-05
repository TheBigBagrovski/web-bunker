<?php 

require_once('register-db-connect.php');

if(isset($_GET['token']) && !empty($_GET['token'])){
    $token = $_GET['token'];
}else{
    exit("<p><strong>Ошибка!</strong> Отсутствует проверочный код.</p>");
}
  
if(isset($_GET['mail']) && !empty($_GET['mail'])){
    $mail = $_GET['mail'];
}else{
    exit("<p><strong>Ошибка!</strong> Отсутствует адрес электронной почты.</p>");
}

$query_select_user = $dbregister->query("SELECT `token` FROM `users-to-confirm` WHERE `mail` = '".$mail."'");
 
//Если ошибок в запросе нет
if(($row = $query_select_user->fetch_assoc()) != false){
    //Если такой пользователь существует
    if($query_select_user->num_rows == 1){
        //Проверяем совпадает ли token
        if($token == $row['token']){
            $query_update_user = $dbregister->query("UPDATE `users` SET `mail_confirm` = 1 WHERE `mail` = '".$mail."'");
            if(!$query_update_user){
                die("Ошибка! Сбой при обновлении статуса пользователя.");
            }else{
                //Удаляем данные пользователя из временной таблицы confirm_users
                $query_delete = $dbregister->query("DELETE FROM `users-to-confirm` WHERE `mail` = '".$mail."'");
                if(!$query_delete){
                    die("Сбой при удалении данных пользователя из временной таблицы");
                }else{
                    require_once("header.php");
                        echo '<h1>Почта успешно подтверждена!</h1>';
                        echo '<div class="outer" id="activation-message"><div class="container"><div class="inner"><p>Теперь Вы можете войти в свой аккаунт.</p></div></div></div>';
                    require_once("footer.php");
                }
                $query_delete->close();
            }
            $query_update_user->close();        
        }else{
            die("Ошибка! Неправильный проверочный код");
        }
    }else{
        die("Ошибка!Такой пользователь не зарегистрирован");
    }
}else{
    die("Ошибка! Сбой при выборе пользователя из БД");
}
 
$query_select_user->close();
$dbregister->close();
