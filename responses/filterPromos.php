<?php 

include $_SERVER['DOCUMENT_ROOT']."/database/db.php"; 

$userId = $_GET['userId'];

// получить массив лайкнутых

if ($userId == 'guest') {
    $likedPromos = [];
} else {
    // КАКИМ ПРОМО ЮЗЕР СТАВИЛ ЛАЙКИ
    $currUserLikesQuery = "SELECT promo_id FROM `likes` WHERE user_id=$userId";
    $result = mysqli_query($link, $currUserLikesQuery) or die("Ошибка " . mysqli_error($link));

    // ВСЕ ЛАЙКНУТЫЕ ПРОМО ЮЗЕРА В МАССИВЕ
    $likedPromos = [];
    
    while ($likedPromo = mysqli_fetch_assoc($result)) {
        $likedPromos[] = $likedPromo['promo_id'];
    } 
}

//

$category = $_GET["category"];
$subcategory = $_GET["subcategory"]; 
$sortby = $_GET["sortby"]; 

switch($sortby) {
    case 'new':
        $ORDERBY = 'promo_id DESC'; 
        break;
    case 'expire': 
        $ORDERBY = 'ending_date';
        break;
    case 'popularity':
        $ORDERBY = 'bought_amount DESC';
        break;
    case 'likes': 
        $ORDERBY = 'likes_amount DESC';
        break;
}

if ($subcategory == "Все") {
    
    $mainQueryPart ="SELECT fir.promo_id, category_id, subcategory, image, title, ending_date, likes_amount, bought_amount FROM (SELECT p.*, COUNT(l.promo_id) as likes_amount FROM promos p left join likes l on p.promo_id = l.promo_id WHERE p.category_id=$category GROUP BY p.promo_id) fir left JOIN (SELECT p.promo_id, COUNT(up.promo_id) as bought_amount FROM promos p left join users_promos up on p.promo_id = up.promo_id WHERE p.category_id=$category GROUP BY p.promo_id) sec on sec.promo_id=fir.promo_id";
    $promoInfoQuery = $mainQueryPart . " ORDER BY $ORDERBY";

} else {
    $mainQueryPart ="SELECT fir.promo_id, category_id, subcategory, image, title, ending_date, likes_amount, bought_amount FROM (SELECT p.*, COUNT(l.promo_id) as likes_amount FROM promos p left join likes l on p.promo_id = l.promo_id WHERE p.category_id=$category and p.subcategory='$subcategory' GROUP BY p.promo_id) fir left JOIN (SELECT p.promo_id, COUNT(up.promo_id) as bought_amount FROM promos p left join users_promos up on p.promo_id = up.promo_id WHERE p.category_id=$category and p.subcategory='$subcategory' GROUP BY p.promo_id) sec on sec.promo_id=fir.promo_id";
    $promoInfoQuery = $mainQueryPart . " ORDER BY $ORDERBY";
}

    $result = mysqli_query($link, $promoInfoQuery) or die("Ошибка выполнения запроса" . mysqli_error($link));
    $filteredCards = [];

    while ($card = mysqli_fetch_assoc($result)) {

        $image = $card['image'];
        $title = $card['title'];
        $ending_date = $card['ending_date'];
        $likesAmount = $card['likes_amount'];
        $boughtAmount = $card['bought_amount'];
        $promoId = $card['promo_id'];

        $isLiked = in_array($promoId, $likedPromos) ? 'liked.svg' : 'like.svg';
        
        $filteredCards[] = "
        <div class='card'>
            <a href='promo.php?promoId=$promoId'>
            <img src='/img/promos/$image' alt='promo' class='promo-img'>
            <div class='promo-info'>
                <h3 class='promo-heading'>$title</h3>
                <div class='ending-rating-wrapper'>
                    <div class='ending'>
                        <img src='/img/clock.svg' alt='clock'>
                        <span class='ending-info'>$ending_date</span>
                    </div>
                    <div class='rating'>
                        <img src='/img/people.svg' alt='people'>
                        <span class='rating-info'>$boughtAmount</span>
                    </div>
                </div>
            </div>
            </a>
            <div class='like-block' data-promo-id=$promoId data-user-id=$userId>
                <img src='/img/$isLiked' alt='like' class='like-image'>
                <span class='like-count'>$likesAmount</span>
            </div>
        </div>
        ";    
    }

    echo json_encode($filteredCards);


?>