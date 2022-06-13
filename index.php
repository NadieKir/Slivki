<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/icon.svg" type="image/svg">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/main.css">
    <title>Slivki</title>
</head>
<body>

    <?php require 'partials/header.php' ?>
    <?php require 'partials/menu.php' ?>

    <?php require 'partials/getLikedPromos.php' ?>

    <section class="most-popular-promos">
        <div class="container">
            <h2 class="heading img-heading">Хиты</h2>

            <?php
                $hitPromosQuery = "SELECT fir.promo_id, category_id, subcategory, image, title, ending_date, likes_amount, bought_amount FROM (SELECT p.*, COUNT(l.promo_id) as likes_amount FROM promos p left join likes l on p.promo_id = l.promo_id GROUP BY p.promo_id) fir left JOIN (SELECT p.promo_id, COUNT(up.promo_id) as bought_amount FROM promos p left join users_promos up on p.promo_id = up.promo_id GROUP BY p.promo_id) sec on sec.promo_id=fir.promo_id ORDER BY bought_amount DESC LIMIT 6";
                $result = mysqli_query($link, $hitPromosQuery) or die("Ошибка выполнения запроса" . mysqli_error($link));
            ?>

            <div class="owl-carousel owl-theme">

                <?php 
                    while ($card = mysqli_fetch_assoc($result)) {
                ?>
                        <div class="card">
                            <a href="pages/promo.php?promoId=<?=@$card['promo_id']?>">
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
                ?>

            </div>
        </div>
    </section>

    <section class="banner">
        <div class="container">
            <img src="img/banner.gif" alt="banner" class="banner" width="100%">
        </div>
    </section>

    <section class="promos-by-category">
        <div class="container">

            <div class="category">

            <?php
                $entertainmentIdQuery="SELECT category_id FROM categories WHERE category='Развлечения'";
                $result = mysqli_query($link, $entertainmentIdQuery) or die("Ошибка выполнения запроса" . mysqli_error($link));
                $entertainmentId = mysqli_fetch_assoc($result)['category_id'];

                $allEntertainmentsQuery="SELECT * FROM promos WHERE category_id='$entertainmentId'";
                $result = mysqli_query($link, $allEntertainmentsQuery) or die("Ошибка выполнения запроса" . mysqli_error($link));
                $numOfEntertainments = mysqli_num_rows($result);

                $firstEntertainmentsQuery = "SELECT fir.promo_id, category_id, subcategory, image, title, ending_date, likes_amount, bought_amount FROM (SELECT p.*, COUNT(l.promo_id) as likes_amount FROM promos p left join likes l on p.promo_id = l.promo_id WHERE p.category_id=$entertainmentId GROUP BY p.promo_id) fir left JOIN (SELECT p.promo_id, COUNT(up.promo_id) as bought_amount FROM promos p left join users_promos up on p.promo_id = up.promo_id WHERE p.category_id=$entertainmentId GROUP BY p.promo_id) sec on sec.promo_id=fir.promo_id ORDER BY promo_id DESC LIMIT 4";
                $result = mysqli_query($link, $firstEntertainmentsQuery) or die("Ошибка выполнения запроса" . mysqli_error($link));
            ?>

                <h2 class="heading">Развлечения <span class="category-promo-count">(<?=@$numOfEntertainments ?>)</span></h2>
                <div class="cards-wrapper">
                    
                <?php 
                    while ($card = mysqli_fetch_assoc($result)) {
                ?>
                        <div class="card">
                            <a href="pages/promo.php?promoId=<?=@$card['promo_id']?>">
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
                ?>

                </div>
                <a href="pages/category.php?category=<?=@$entertainmentId?>" class="view-all-btn">Смотреть все</a>
            </div>


            <div class="category">

            <?php 
                $foodIdQuery="SELECT category_id FROM categories WHERE category='Еда'";
                $result = mysqli_query($link, $foodIdQuery) or die("Ошибка выполнения запроса" . mysqli_error($link));
                $foodId = mysqli_fetch_assoc($result)['category_id'];

                $allFoodQuery="SELECT * FROM promos WHERE category_id='$foodId'";
                $result = mysqli_query($link, $allFoodQuery) or die("Ошибка выполнения запроса" . mysqli_error($link));
                $numOfFood = mysqli_num_rows($result);

                $firstFoodsQuery="SELECT fir.promo_id, category_id, subcategory, image, title, ending_date, likes_amount, bought_amount FROM (SELECT p.*, COUNT(l.promo_id) as likes_amount FROM promos p left join likes l on p.promo_id = l.promo_id WHERE p.category_id=$foodId GROUP BY p.promo_id) fir left JOIN (SELECT p.promo_id, COUNT(up.promo_id) as bought_amount FROM promos p left join users_promos up on p.promo_id = up.promo_id WHERE p.category_id=$foodId GROUP BY p.promo_id) sec on sec.promo_id=fir.promo_id ORDER BY promo_id DESC LIMIT 4";
                $result = mysqli_query($link, $firstFoodsQuery) or die("Ошибка выполнения запроса" . mysqli_error($link));
            ?>

                <h2 class="heading">Еда <span class="category-promo-count">(<?=@$numOfFood ?>)</span></h2>
                <div class="cards-wrapper">
                    
                <?php 
                    while ($card = mysqli_fetch_assoc($result)) {
                ?>
                        <div class="card">
                            <a href="pages/promo.php?promoId=<?=@$card['promo_id']?>">
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
                ?>

                </div>
                <a href="pages/category.php?category=<?=@$foodId?>" class="view-all-btn">Смотреть все</a>
            </div>
        </div>
    </section>

    <?php require 'partials/footer.php' ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>