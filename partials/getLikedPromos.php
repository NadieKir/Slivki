<?php 
if ($_SESSION['userPhone'] != '') {

    // ID ЮЗЕРА
    $currUserPhone = $_SESSION['userPhone'];
    $query="SELECT user_id FROM users WHERE phone = $currUserPhone";
    $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
    $currUserId= mysqli_fetch_assoc($result)['user_id'];

    // КАКИМ ПРОМО ЮЗЕР СТАВИЛ ЛАЙКИ
    $currUserLikesQuery = "SELECT promo_id FROM `likes` WHERE user_id=$currUserId";
    $result = mysqli_query($link, $currUserLikesQuery) or die("Ошибка " . mysqli_error($link));

    // ВСЕ ЛАЙКНУТЫЕ ПРОМО ЮЗЕРА В МАССИВЕ
    $likedPromos = [];
    
    while ($likedPromo = mysqli_fetch_assoc($result)) {
        $likedPromos[] = $likedPromo['promo_id'];
    } 

} else {
    $currUserId = 'guest';
    $likedPromos = [];
}


?>