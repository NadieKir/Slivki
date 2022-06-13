<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/img/icon.svg" type="image/svg">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="/css/searchResult.css">
    <title>Slivki</title>
</head>
<body>

    <?php require '../partials/header.php' ?>
    <?php require '../partials/menu.php' ?>

    <?php require '../partials/getLikedPromos.php' ?>

    <?php 
        $searchWord = $_POST['searchQuery'];

        $searchQuery = "SELECT fir.promo_id, category_id, subcategory, image, title, ending_date, likes_amount, bought_amount FROM (SELECT p.*, COUNT(l.promo_id) as likes_amount FROM promos p left join likes l on p.promo_id = l.promo_id GROUP BY p.promo_id) fir left JOIN (SELECT p.promo_id, COUNT(up.promo_id) as bought_amount FROM promos p left join users_promos up on p.promo_id = up.promo_id GROUP BY p.promo_id) sec on sec.promo_id=fir.promo_id WHERE title LIKE '%$searchWord%'";
        $result = mysqli_query($link, $searchQuery) or die("Ошибка выполнения запроса" . mysqli_error($link));
    ?>

    <section class="search-result-section">
        <div class="container">
            <h2>Результаты по запросу <span class="query"><?=@$_POST['searchQuery']?></span></h2>

            <div class="search-result">
                <?php 
                    if(mysqli_num_rows($result) == 0){
                        echo "<div>По данному запросу ничего не найдено</div>";
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
                    }}
                ?>
            </div>
        </div>
    </section>

    <?php require '../partials/footer.php' ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="../js/searchResult.js"></script>
</body>
</html>