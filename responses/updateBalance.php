<?php 

include $_SERVER['DOCUMENT_ROOT']."/database/db.php"; 

$userPhone = $_GET["userPhone"];

// ЗАНЕСЕНИЕ В БД

$query="UPDATE users SET balance = balance + 0.5 WHERE phone = $userPhone";
$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));

$query="SELECT balance FROM users WHERE phone = $userPhone";
$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));

echo(mysqli_fetch_assoc($result)["balance"]);

?>