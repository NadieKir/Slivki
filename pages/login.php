<?php

if(session_status() != PHP_SESSION_ACTIVE) session_start();

function checkCaptcha() {
    if ($_POST['captcha'] == $_SESSION['captcha']) {
        return true;
    } else {
        return false;
    }
}

function clearString($str) {
    $str = trim($str);
    $str = strip_tags($str); 
    $str = stripslashes($str);

    return $str;
}

include '../database/db.php'; 
require '../partials/authorization.php';
require '../partials/registration.php';

?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/img/icon.svg" type="image/svg">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"> 
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/login.css">
    <title>Slivki</title>
</head>
<body>
    <header class="header">
        <a href="/index.php"><img src="/img/white-logo.svg" alt="logo"></a>
    </header>

    <section class="main-section">

            <div class="main-form">
                <label class="tab authorisation active">Авторизация</label>
                <label class="tab registration">Регистрация</label>
        
                <form id="form-authorization" action="login.php" method="post" class="tab-form active">
                    <div class="box-input">
                        <label>Введите E-mail</label>
                        <input name="auth-email" class="input email" type="text" value="<?=@$_POST["auth-email"]?>">
                        <span class="error"><?=@$authEmailError;?></span>
                    </div>
        
                    <div class="box-input">
                        <label>Введите пароль</label>
                        <input name="auth-password" class="input password" type="password" value="<?=@$_POST["auth-password"]?>">
                        <span class="error"><?=@$authPasswordError;?></span>
                    </div>
        
                    <input type="hidden" name="go-auth" value="5">
                    <input type="submit" class="button" value="Войти">
                </form>
        
                <form id="form-registration" action="login.php" method="post" class="tab-form">
                    <div class="box-input">
                        <label>Введите свое имя</label>
                        <input name="name" class="input name" type="text" value="<?=@$_POST["name"];?>">
                        <span class="error"><?=$nameError;?></span>
                    </div>

                    <div class="box-input">
                        <label>Введите E-mail</label>
                        <input name="email" class="input email" type="text" value="<?=@$_POST["email"]?>">
                        <span class="error"><?=@$emailError;?></span>
                    </div>
        
                    <div class="box-input">
                        <label>Введите номер телефона</label>
                        <input name="phone" class="input phone" type="text" value="<?=@$_POST["phone"]?>">
                        <span class="error"><?=@$phoneError;?></span>
                    </div>
        
                    <div class="box-input">
                        <label>Введите пароль</label>
                        <input name="first-password" class="input password" type="password">
                        <span class="error"><?=@$firstPasswordError;?></span>
                    </div>

                    <div class="box-input">
                        <label>Повторите пароль</label>
                        <input name="second-password" class="input repeat-password" type="password">
                        <span class="error"><?=@$secondPasswordError;?></span>
                    </div>

                    <div class="captcha">
                        <div class="captcha-img-btn">
                            <img id="capcha-image" class="captcha__image" src="../captcha/captcha.php" width="120" alt="captcha">
                            <a href="javascript:void(0);" class="captcha__refresh-btn" onclick="document.getElementById('capcha-image').src='../captcha/captcha.php?rid=' + Math.random();">Обновить капчу</a>
                        </div>
                        <div class="box-input captcha-input">
                            <label>Код на картинке</label>
                            <input class="input" name="captcha" type="text">
                            <span class="error"><?=@$captchaError;?></span>
                        </div>
                    </div>
        
                    <input type="hidden" name="go-reg" value="5">
                    <input type="submit" class="button" value="Зарегистрироваться">

                </form>
        
            </div>

    </section>

    <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
    <script src="/js/login.js"></script>
</body>
</html>