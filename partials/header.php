<?php 
    if(session_status() != PHP_SESSION_ACTIVE) session_start();

    if(isset($_POST["logInLogOut"])) {

        if($_SESSION["userName"]) {
            $_SESSION["userName"] = '';
            $_SESSION["userPhone"] = '';
            $_SESSION["userBalance"] = '';

            print "<script language='Javascript' type='text/javascript'>
            alert(`Вы успешно вышли из аккаунта!`);
            function reload(){top.location = '/index.php'};
            reload();
            </script>";
        } else {
            print "<script language='Javascript' type='text/javascript'>
            function reload(){top.location = '/pages/login.php'};
            reload();
            </script>";
        }

        unset($_POST["logInLogOut"]);
}
?>

<header class="header">
    <div class="container">
        <a href="../index.php"><img src="../img/logo.svg" alt="logo" class="logo" height="35px"></a>
        
        <form class="search" method="POST" action="/../pages/searchResult.php">
            <input type="text" name="searchQuery" class="search-input" required>
            <button class="search-btn" type="submit">
                <img src="../img/search.svg" alt="search">
            </button>
        </form>

        <div class="personal-account">
            <img src="../img/user.svg" alt="user-photo" width="40px">
            <div class="user-name-login-btn-wrapper">
                <a href="#"><span class="user-name"><?php
                    if ($_SESSION["userName"]) echo $_SESSION["userName"];
                    else echo 'Гость';
                ?></span></a>
                <form method="post">
                    <input type="hidden" name="logInLogOut" value="">
                    <input type="submit" value="Войти" class="login-btn">
                </form>
            </div>
        </div>
    </div>
</header>

<script>
    const userName = document.querySelector('.user-name');
    if (userName.innerHTML == 'Гость') {
        userName.parentElement.removeAttribute('href');
    } else {
        userName.parentElement.setAttribute('href', '/pages/account.php');
    }

    const logInLogOutBtn = document.querySelector('.login-btn');
    if (userName.innerHTML == 'Гость') {
        logInLogOutBtn.setAttribute('value', 'Войти');
    } else {
        logInLogOutBtn.setAttribute('value', 'Выйти');
    }

    // ПОИСК

    
</script>
