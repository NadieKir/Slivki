<?php  

include $_SERVER['DOCUMENT_ROOT']."/database/db.php"; 

$userId = $_GET['userId'];
$promoId = $_GET['promoId'];

$query="UPDATE users_promos SET promo_is_used=1 WHERE user_id=$userId AND promo_id=$promoId and promo_is_used=0";
$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));

// КАКИЕ КУПЛЕННЫЕ ПРОМО ЮЗЕР НЕ ИСПОЛЬЗОВАЛ
$currUserBoughtQuery = "SELECT promo_id FROM users_promos WHERE user_id=$userId and promo_is_used=0";
$result = mysqli_query($link, $currUserBoughtQuery) or die("Ошибка " . mysqli_error($link));

// ВСЕ id КУПЛЕННЫХ ПРОМО КОТОРЫЕ ЮЗЕР НЕ ИСПОЛЬЗОВАЛ
$boughtPromos = [];

while ($boughtPromo = mysqli_fetch_assoc($result)) {
    $boughtPromos[] = $boughtPromo['promo_id'];
} 

$updatedBoughts =[];

if(count($boughtPromos) == 0) {
    echo 0;
} else {
    for ($i = 0; $i < count($boughtPromos); $i++) {
        $query = "SELECT fir.promo_id, category_id, subcategory, image, title, promocode, ending_date, price, likes_amount, bought_amount FROM (SELECT p.*, COUNT(l.promo_id) as likes_amount FROM promos p left join likes l on p.promo_id = l.promo_id GROUP BY p.promo_id) fir left JOIN (SELECT p.promo_id, COUNT(up.promo_id) as bought_amount FROM promos p left join users_promos up on p.promo_id = up.promo_id GROUP BY p.promo_id) sec on sec.promo_id=fir.promo_id WHERE fir.promo_id=$boughtPromos[$i]";
        $result = mysqli_query($link, $query) or die("Ошибка выполнения запроса" . mysqli_error($link));
        $card = mysqli_fetch_assoc($result);

        $image =$card['image'];
        $title= $card['title'];
        $endingDate =$card['ending_date'];
        $promoId = $card['promo_id'];
        $likesAmount = $card['likes_amount'];
        $boughtAmount = $card['bought_amount'];

        $updatedBoughts[] = "<div class='card'>
            <a href='promo.php?promoId=$promoId'>
                <img src='/img/promos/$image' alt='promo' class='promo-img'>
                <div class='promo-info'>
                    <h3 class='promo-heading'>$title</h3>
                    <div class='ending-rating-wrapper'>
                        <div class='ending'>
                            <img src='/img/clock.svg' alt='clock'>
                            <span class='ending-info'>$endingDate</span>
                        </div>
                        <div class='rating'>
                            <img src='/img/people.svg' alt='people'>
                            <span class='rating-info'>$bought_amount</span>
                        </div>
                    </div>
                </div>
            </a>
            <div class='delete-block' data-promo-id=$promoId data-user-id=$userId>
                <img src='/img/cross.svg' alt='delete' class='delete-image'>
            </div>
        </div>";
    }

    echo json_encode($updatedBoughts);
}




?>