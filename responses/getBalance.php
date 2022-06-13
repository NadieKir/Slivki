<?php

    include $_SERVER['DOCUMENT_ROOT']."/database/db.php"; 

    $userPhone = $_GET["userPhone"];
    
    $query="SELECT balance FROM users WHERE phone = $userPhone";
    $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
    $res = mysqli_fetch_assoc($result);

    echo($res['balance']);
?>