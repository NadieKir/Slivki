<?php
if (isset($_POST["go-reg"])) {

    // ОСТАТЬСЯ НА ВКЛАДКЕ РЕГИСТАРЦИЯ ПРИ ОТПРАВКЕ ФОРМЫ, 
    // НЕПРОШЕДШЕЙ ВАЛИДАЦИЮ

    print "<script language='Javascript' type='text/javascript'>
            let isRegBtnPressed = true;
    </script>";

    // ВАЛИДАЦИЯ ФИО

    $nameError = '';
    $name = $_POST["name"];
    clearString($name);

    if($name == '') {
        $nameError .= "Заполните поле";
    } else if(!preg_match('/^[a-zа-я\s\-]{2,}$/iu', $name)) {
        $nameError .= "Введенное имя не соответствует требованиям";
    } 

    // ВАЛИДАЦИЯ ПОЧТЫ

    $emailError = '';
    $email = $_POST["email"];
    clearString($email);

    if($email == '') {
        $emailError .= "Заполните поле";
    } else if(!preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9-]+.+.[A-Z]{2,4}$/iu', $email)) {
        $emailError .= "Введенная почта не соответствует требованиям";
    } else {

        $query="SELECT user_id FROM users WHERE email='$email'";
        $result = mysqli_query($link, $query) or die("Ошибка выполнения запроса" . mysqli_error($link));
        if ($result){
            $row = mysqli_fetch_row($result);
            if (!empty($row[0])) $emailError .= "Пользователь с данной почтой уже существует"; 
        }
    }

    // ВАЛИДАЦИЯ ТЕЛЕФОНА

    $phoneError = '';
    $phone = $_POST["phone"];
    clearString($phone);

    if($phone == '') {
        $phoneError .= "Заполните поле";
    } else if(!preg_match('/^(\+?375|80)\s?\(?(29|44|33|25)\)?\s?\d{3}\-?\d{2}\-?\d{2}/', $phone)) {
        $phoneError .= "Введенный телефон не соответствует требованиям";
    } else {
        $query="SELECT user_id FROM users WHERE phone='$phone'";
        $result = mysqli_query($link, $query) or die("Ошибка выполнения запроса" . mysqli_error($link));
        if ($result){
            $row = mysqli_fetch_row($result);
            if (!empty($row[0])) $phoneError .= "Пользователь с данным телефоном уже существует"; 
        }
    }

    // ВАЛИДАЦИЯ ПАРОЛЯ

    $firstPasswordError = '';
    $firstPassword = $_POST["first-password"];
    clearString($firstPassword);

    if($firstPassword == '') {
        $firstPasswordError .= "Заполните поле";
    } else if(!preg_match('/^[a-zа-я\d]{8,50}$/ui', $firstPassword)) {
        $firstPasswordError .= "Введенный пароль не соответствует требованиям";
    }

    // ВАЛИДАЦИЯ ПОВТОРНОГО ПАРОЛЯ

    $secondPasswordError = '';
    $secondPassword = $_POST["second-password"];
    clearString($secondPassword);

    if($secondPassword == '') {
        $secondPasswordError .= "Заполните поле";
    } else if($secondPassword != $firstPassword) {
        $secondPasswordError .= "Пароли не совпадают";
    }

    // ВАЛИДАЦИЯ КАПЧИ

    $captchaError = '';
    $captcha = $_POST["captcha"];
    clearString($captcha);

    if($captcha == '') {
        $captchaError = 'Заполните поле';
    } else {
        if(checkCaptcha() == false) $captchaError = 'Неверно введены символы';
    }
    
    // ЗАНЕСЕНИЕ В БД

    if ($nameError.$emailError.$firstPasswordError.$secondPasswordError.$phoneError.$captchaError == '') {

        $password = $firstPassword;

        $salt = mt_rand(100, 999);
        $password = md5(md5($password).$salt);
        $query="INSERT INTO users (name, email, phone, password, salt, balance) VALUES ('$name','$email','$phone','$password','$salt', 2)";
        $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
        if ($result) 
        {
            $query="SELECT * FROM users WHERE email='$email'";
            $rez = mysqli_query($link, $query);
            if ($rez) {
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
            } else {
                print "<script language='Javascript' type='text/javascript'>
                alert('Вы не были зарегистрированы.');
                </script>";
            }
        }

    }

    unset($_POST["go-reg"]);
}
?>