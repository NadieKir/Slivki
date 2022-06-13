<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/img/icon.svg" type="image/svg">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="/css/category.css">
    <title>Slivki</title>
</head>
<body>

    <?php require '../partials/header.php' ?>
    <?php require '../partials/menu.php' ?>

    <?php require '../partials/getLikedPromos.php' ?>

    <section class="category-block">
        <div class="container">
            <div class="category-btns">
                <div class="filter-btns btns-wrapper">

                    <?php 
                        $chosenCategory = $_GET["category"];
                        echo "<button data-category='$chosenCategory' data-subcategory='Все' class='active-filter-btn'>Все</button>";

                        $query="SELECT DISTINCT subcategory FROM promos WHERE category_id='$chosenCategory'";
                        $result = mysqli_query($link, $query) or die("Ошибка выполнения запроса" . mysqli_error($link));

                        for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                            $row = mysqli_fetch_row($result);
                            echo "<button data-category='$chosenCategory' data-subcategory='$row[0]'>$row[0]</button>";
                        }
                    ?>

                </div>
                <div class="sort-btns btns-wrapper">
                    <button data-sortby="new" class="active-sort-btn">Новые</button>
                    <button data-sortby="expire">Скоро истекут</button>
                    <button data-sortby="popularity">По популярности</button>
                    <button data-sortby="likes">По лайкам</button>
                </div>
            </div>
            <div class="category-query-result">

                <?php 

                    $promoInfoQuery = "SELECT fir.promo_id, category_id, subcategory, image, title, ending_date, likes_amount, bought_amount FROM (SELECT p.*, COUNT(l.promo_id) as likes_amount FROM promos p left join likes l on p.promo_id = l.promo_id WHERE p.category_id=$chosenCategory GROUP BY p.promo_id) fir left JOIN (SELECT p.promo_id, COUNT(up.promo_id) as bought_amount FROM promos p left join users_promos up on p.promo_id = up.promo_id WHERE p.category_id=$chosenCategory GROUP BY p.promo_id) sec on sec.promo_id=fir.promo_id ORDER BY promo_id DESC";
                    $result = mysqli_query($link, $promoInfoQuery) or die("Ошибка выполнения запроса" . mysqli_error($link));

                    if(mysqli_num_rows($result) == 0) {
                        echo "<div style='height: 130px'> Пока у нас нет таких акций! </div>";
                    } else {

                        while ($card = mysqli_fetch_assoc($result)) {
                            ?>
                            <div class="card">
                                <a href="promo.php?promoId=<?=@$card['promo_id']?>">
                                    <img src="/img/promos/<?=@$card['image']?>" alt="promo" class="promo-img">
                                    <div class="promo-info">
                                        <h3 class="promo-heading"><?=@$card['title']?></h3>
                                        <div class="ending-rating-wrapper">
                                            <div class="ending">
                                                <img src="/img/clock.svg" alt="clock">
                                                <span class="ending-info"><?=@$card['ending_date']?></span>
                                            </div>
                                            <div class="rating">
                                                <img src="/img/people.svg" alt="people">
                                                <span class="rating-info"><?=@$card['bought_amount']?></span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class="like-block" data-promo-id='<?=@$card['promo_id']?>' data-user-id='<?=@$currUserId?>'>
                                    <img src="/img/<?=@ in_array($card['promo_id'], $likedPromos) ? 'liked.svg' : 'like.svg' ?>" alt="like" class="like-image">
                                    <span class="like-count"><?=@$card['likes_amount']?></span>
                                </div>
                            </div>
    
                            <?php
                        }
                    }
                ?>

            </div>
        </div>
    </section>

    <?php require '../partials/footer.php' ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="/js/category.js"></script>
</body>
</html>