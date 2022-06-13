<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/img/icon.svg" type="image/svg">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="/css/promo.css">
    <title>Slivki</title>
</head>
<body>

    <?php require '../partials/header.php' ?>
    <?php require '../partials/menu.php' ?>

    <?php require '../partials/getLikedPromos.php' ?>

    <?php 
        $promoId =$_GET['promoId'];

        $promoQuery = "SELECT fir.promo_id, category_id, subcategory, image, title, ending_date, price, likes_amount, bought_amount FROM (SELECT p.*, COUNT(l.promo_id) as likes_amount FROM promos p left join likes l on p.promo_id = l.promo_id GROUP BY p.promo_id) fir left JOIN (SELECT p.promo_id, COUNT(up.promo_id) as bought_amount FROM promos p left join users_promos up on p.promo_id = up.promo_id GROUP BY p.promo_id) sec on sec.promo_id=fir.promo_id WHERE fir.promo_id=$promoId";
        $result = mysqli_query($link, $promoQuery) or die("Ошибка выполнения запроса" . mysqli_error($link));
        $card = mysqli_fetch_assoc($result);
    ?>

    <section class="brief-promo">
        <div class="container">
            <h1 class="promo-main-heading"><?=@$card['title']?> </h1>
            <div class="img-info-wrapper">
                <img src="/img/promos/<?=@$card['image']?>" alt="promo" class="promo-img">
                <div class="brief-promo-info">
                    <p class="promo-data">Дата завершения: <span class="ending"><?=@$card['ending_date']?></span></p>
                    <div class="line"></div>
                    <p class="promo-data">Промокодов куплено: <span class="rating"><?=@$card['bought_amount']?></span></p>
                    <div class="line"></div>
                    <p class="promo-data">Стоимость промокода: <span class="price"><?=@$card['price']?></span> BYN</p>
                    <div class="line"></div>

                    <div class="btn-wrapper">
                        <?php 
                        $usersPromos = [];

                        $usersPromosQuery = "SELECT promo_id FROM users_promos WHERE user_id='$currUserId' and promo_is_used = 0";
                        $result = mysqli_query($link, $usersPromosQuery) or die("Ошибка выполнения запроса" . mysqli_error($link));
                        
                        while($res = mysqli_fetch_assoc($result)) {
                            $usersPromos[] = $res['promo_id'];
                        }
                        
                        if(in_array($promoId, $usersPromos)) {
                        ?>

                        <div class="you-have-promo-message" >Промокод уже ваш</div>
                        
                        <?php
                        } else {
                        ?>

                        <div><button class="buy-btn" data-promo-id='<?=@$promoId?>' data-user-id='<?=@$currUserId?>' >Получить промокод</button></div>
                        
                        <?php
                        }
                        ?>
                        
                        <div class="like-block" data-promo-id='<?=@$card['promo_id']?>' data-user-id='<?=@$currUserId?>' class="like-image" width="25px">
                            <img src="/img/<?=@ in_array($card['promo_id'], $likedPromos) ? 'liked.svg' : 'like.svg' ?>" alt="like" class="like-image" width="25px">
                            <span class="like-count"><?=@$card['likes_amount']?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="buy-promo-modal-overlay hidden">

        <?php 
            // БАЛАНС ЮЗЕРА
            $balanceQuery = "SELECT balance FROM users WHERE user_id='$currUserId'";
            $result = mysqli_query($link, $balanceQuery) or die("Ошибка выполнения запроса" . mysqli_error($link));
            if(mysqli_num_rows($result) != 0) {
                $userBalance = mysqli_fetch_assoc($result)['balance'];
            }

            // СТОИМОСТЬ ПРОМОКОДА
            $priceQuery = "SELECT price,title,promocode FROM promos WHERE promo_id=$promoId";
            $result = mysqli_query($link, $priceQuery) or die("Ошибка выполнения запроса" . mysqli_error($link));
            $res = mysqli_fetch_assoc($result);

            $promoPrice = $res['price'];
            $promoTitle = $res['title'];
            $promoCode = $res['promocode'];

            if($userBalance >= $promoPrice) {
                $getBalanceQuery = "SELECT balance FROM users WHERE user_id = $currUserId";
                $result = mysqli_query($link, $getBalanceQuery) or die("Ошибка выполнения запроса" . mysqli_error($link));
                $updatedBalance = mysqli_fetch_assoc($result)['balance'] - $promoPrice;
        ?>
                <div class="buy-promo-modal" data-state='success'>  
                    <h3 class="buy-promo-heading">Промокод <span class="green">успешно</span> куплен!</h3>
                    <div class="bought-promo-brief">
                        <p class="promo-data">Акция: <span class="promo-name"><?=@$promoTitle?></span></p>
                        <div class="line"></div>
                        <p class="promo-data">Ваш промокод: <span class="promocode"><?=@$promoCode?></span></p>
                        <div class="line"></div>
                        <p class="promo-data last-promo-data">Остаток на балансе: <span class="balance"><?=@$updatedBalance?></span> BYN</p>
                        <div class="line"></div>
                    </div>
                </div> 
        <?php
            } else {
        ?>
            <div class="buy-promo-modal" data-state='fail'>  
                <h3 class="buy-promo-heading">На вашем балансе <span class="red">недостаточно средств</span></h3>
                <div class="bought-promo-brief">
                    <p class="promo-data">Стоимость промокода: <span class="promo-price"><?=@$promoPrice?></span> BYN</p>
                    <div class="line"></div>
                    <p class="promo-data">Ваш баланс: <span class="red"><span class="balance"><?=@$userBalance?></span> BYN</span></p>
                    <div class="line"></div>
                </div>
                <a href="account.php?activeTab=4" class="account-btn">Пополнить баланс</a>
            </div> 
        <?php
            }
        ?>
                
        </div>
    </section>

    <section class="full-promo-desc">
        <div class="container">
            <div class="conditions">
                <h2 class="secondary-promo-heading">Условия</h2>

                <?php 
                // ПОЛУЧИТЬ МАССИВ УСЛОВИЙ, КОНТАКТОВ, ВРЕМЯ РАБОТЫ

                $conditionsQuery = "SELECT terms,contacts,working_hours,address FROM promos WHERE promo_id=$promoId";
                $result = mysqli_query($link, $conditionsQuery) or die("Ошибка выполнения запроса" . mysqli_error($link));
                $res = mysqli_fetch_assoc($result);

                $termsStr = $res['terms'];
                $contactsStr = $res['contacts'];
                $workingHoursStr = $res['working_hours'];
                $addressStr = $res['address'];

                $termsArr = explode('@', $termsStr);
                $contactsArr = explode('@', $contactsStr);
                $workingHoursArr = explode('@', $workingHoursStr);
                $addressArr = explode('@', $addressStr);

                ?>

                <ul class="promo-list dotted-list">
                    <?php 
                        for($i = 0; $i < count($termsArr); $i++) {
                    ?>
                        <li><?=@$termsArr[$i]?></li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
            <div class="about-owner">
                <div class="contacts">
                    <h2 class="secondary-promo-heading">Контакты</h2>

                    <ul class="promo-list line-list">
                        <?php 
                            for($i = 0; $i < count($contactsArr); $i++) {
                        ?>
                            <li><?=@$contactsArr[$i]?></li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
                <div class="working-hours">
                    <h2 class="secondary-promo-heading">Время работы</h2>
                    <ul class="promo-list line-list">
                        <?php 
                            for($i = 0; $i < count($workingHoursArr); $i++) {
                        ?>
                            <li><?=@$workingHoursArr[$i]?></li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
                <div class="address">
                    <h2 class="secondary-promo-heading">Адрес</h2>
                    <ul class="promo-list line-list">
                        <?php 
                            for($i = 0; $i < count($addressArr); $i++) {
                        ?>
                            <li><?=@$addressArr[$i]?></li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <?php require '../partials/footer.php' ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="/js/promo.js"></script>
    
</body>
</html>