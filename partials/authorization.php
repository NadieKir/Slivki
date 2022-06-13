<?php
if (isset($_POST["go-auth"])) {

    // ВАЛИДАЦИЯ EMAIL

    $authEmailError = '';
    $email = $_POST["auth-email"];
    clearString($email);

    if($email == '') {
        $authEmailError .= "Заполните поле";
    } else if(!preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9-]+.+.[A-Z]{2,4}$/iu', $email)) {
        $authEmailError .= "Введенная почта не соответствует требованиям";
    } else {
        $query="SELECT user_id FROM users WHERE email='$email'";
        $result = mysqli_query($link, $query) or die("Ошибка выполнения запроса" . mysqli_error($link));
        if ($result){
            $row = mysqli_fetch_row($result);
            if (empty($row[0])) $authEmailError .= "Данная почта не зарегистрирована"; 
        }
    }

    // ВАЛИДАЦИЯ ПАРОЛЯ

    $authPasswordError = '';
    $password = $_POST["auth-password"];
    clearString($password);

    if($password == '') {
        $authPasswordError .= "Заполните поле";
    } else if(!preg_match('/^[a-zа-я\d]{8,50}$/ui', $password)) {
        $authPasswordError .= "Данный пароль не соответствует требованиям";
    }

    // ПРОВЕРКА В БД НАЛИЧИЯ ЮЗЕРА

    if ($authEmailError.$authPasswordError == '') {

        $passwordQuery="SELECT password FROM users WHERE email='$email'";
        $saltQuery="SELECT salt FROM users WHERE email='$email'";

        $passwordResult = mysqli_query($link, $passwordQuery) or die("Ошибка выполнения запроса" . mysqli_error($link));
        $saltResult = mysqli_query($link, $saltQuery) or die("Ошибка выполнения запроса" . mysqli_error($link));

        if ($passwordResult && $saltResult) {
            $passwordRow = mysqli_fetch_row($passwordResult);
            $saltRow = mysqli_fetch_row($saltResult);

            if(md5(md5($password).$saltRow[0]) == $passwordRow[0]) {
                $userExists = true; 
            } else {
                $userExists = false; 
                print "<script language='Javascript' type='text/javascript'>
                alert('Такого пользователя не существует!');
                </script>";
            }
        }
    } 

    // ВХОД

    if ($userExists) {

        $userInfoQuery="SELECT name,phone,balance FROM users WHERE email='$email'";
        $userInfoResult = mysqli_query($link, $userInfoQuery) or die("Ошибка выполнения запроса" . mysqli_error($link));
        
        if ($userInfoResult) {
            $userInfoRow = mysqli_fetch_row($userInfoResult);
            $_SESSION["userName"] = $userInfoRow[0];
            $_SESSION["userPhone"] = $userInfoRow[1];
        }

        print "<script language='Javascript' type='text/javascript'>
        alert(`Вы успешно вошли в аккаунт!`);
        function reload(){top.location = 'account.php'};
        reload();
        </script>";
    }

    unset($_POST["go-auth"]);
}
?>