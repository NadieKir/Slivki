<?php  

include $_SERVER['DOCUMENT_ROOT']."/database/db.php"; 

if ($_GET['userId'] != 'guest') {
    $userId = $_GET['userId'];
    $promoId = $_GET['promoId'];

    // СТОИТ ЛИ УЖЕ ЛАЙК?
    $query="SELECT * FROM likes WHERE user_id = $userId and promo_id=$promoId";
    $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
    $isLikeSet= mysqli_num_rows($result);

    if ($isLikeSet) {
        // УБИРАЕМ ЛАЙК
        $query="DELETE FROM likes WHERE user_id = $userId and promo_id=$promoId";
        $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));

    } else {
        // СТАВИМ ЛАЙК
        $query="INSERT likes(user_id, promo_id) VALUES ($userId, $promoId);";
        $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
    }

    echo 1;
} else {
    echo 0;
}

?>