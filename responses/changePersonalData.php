<?php

include $_SERVER['DOCUMENT_ROOT']."/database/db.php"; 

function clearString($str) {
    $str = trim($str);
    $str = strip_tags($str); 
    $str = stripslashes($str);

    return $str;
}

    // ТЕКУЩАЯ ИНФОРМАЦИЯ В БД О ЮЗЕРЕ

    $userId = $_GET['userId'];
    
    $query="SELECT name,phone,password,salt FROM users WHERE user_id=$userId";
    $result = mysqli_query($link, $query) or die("Ошибка выполнения запроса" . mysqli_error($link));

    $res = mysqli_fetch_assoc($result);

    $userHashPassword = $res['password'];
    $userSalt = $res['salt'];

    //

    if($_GET['change'] == 'password') {

        // СОЗДАТЬ ВОЗВРАЩАЕМЫЙ ОБЪЕКТ

        class ChangingData {
            public $message;
            public $isSuccess;
        }
        
        $changingData = new ChangingData;

        // ВАЛИДАЦИЯ СТАРОГО ПАРОЛЯ

        $oldPasswordError = '';
        $oldPassword = $_POST["oldPassword"];
        clearString($oldPassword);

        if(md5(md5($oldPassword).$userSalt) != $userHashPassword) {
            $oldPasswordError .= "Старый пароль введён неверно";
        }

        // ВАЛИДАЦИЯ НОВОГО ПАРОЛЯ

        $newPasswordError = '';
        $newPassword = $_POST["newPassword"];
        clearString($newPassword);

        if(!preg_match('/^[a-zа-я\d]{8,50}$/ui', $newPassword)) {
            $newPasswordError .= "Новый пароль не соответствует требованиям";
        } 

        // ЗАНЕСЕНИЕ В БД

        if($oldPasswordError.$newPasswordError == '') {
            $newSalt = mt_rand(100, 999);
            $newHashedPassword = md5(md5($newPassword).$newSalt);   

            $query = "UPDATE users SET password='$newHashedPassword',salt=$newSalt WHERE user_id=$userId";
            $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
 
            $changingData->message = "Пароль успешно обновлен";
            $changingData->isSuccess = true;
            
            echo json_encode($changingData);
        } else {
            $changingData->message = $oldPasswordError."<br>".$newPasswordError;
            $changingData->isSuccess = false;
            
            echo json_encode($changingData);
        }
    }
?>