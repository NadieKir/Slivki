<?php

include $_SERVER['DOCUMENT_ROOT']."/database/db.php"; 

$promoId = $_GET['promoId'];
$userId = $_GET['userId'];

$priceQuery = "SELECT price FROM promos WHERE promo_id=$promoId";
$result = mysqli_query($link, $priceQuery) or die("Ошибка выполнения запроса" . mysqli_error($link));
$promoPrice = mysqli_fetch_assoc($result)['price'];

$addPromoQuery = "INSERT users_promos(user_id, promo_id, promo_is_used) VALUES ($userId, $promoId, 0)";
$result = mysqli_query($link, $addPromoQuery) or die("Ошибка выполнения запроса" . mysqli_error($link));

// Остаток на балансе
$updateBalanceQuery = "UPDATE users SET balance = balance-$promoPrice WHERE user_id = $userId";
$result = mysqli_query($link, $updateBalanceQuery) or die("Ошибка выполнения запроса" . mysqli_error($link));

?>