<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/img/icon.svg" type="image/svg">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="/css/account.css">
    <title>Slivki</title>
</head>
<body>

    <?php require '../partials/header.php' ?>
    <?php require '../partials/menu.php' ?>
    <?php require '../partials/getLikedPromos.php' ?>

    <section class="brief-account-info-section">
        <div class="container">
            <h1 class="account-heading">Личный кабинет</h1>
            <div class="brief-account-info-wrapper">
                <img src="/img/user.svg" alt="user" height="135px">
                <div class="brief-account-info">
                    <p class="account-data">Имя: <span class="name"><?=@$_SESSION["userName"]?></span></p>
                    <div class="line"></div>
                    <p class="account-data">Телефон: <span id="phone" class="phone"><?=@$_SESSION["userPhone"]?></span></p>
                    <div class="line"></div>
                    <p class="account-data">Баланс: <span id="balance" class="balance"></span> BYN</p>
                    <div class="line"></div>
                </div>
            </div>
        </div>
    </section>

    <section class="account-tools-section">
        <div class="container">
            <div class="tabs-block-wrapper">
                <div id="tabs">
                    <div class="tab-btn" data-btn="1">Мои промокоды</div>
                    <div class="tab-btn" data-btn="3">Любимые акции</div>
                    <div class="tab-btn" data-btn="2">Изменить пароль</div>
                    <div class="tab-btn" data-btn="4">Пополнить баланс</div>
                </div>
                <div id="contents">

                    <div class="content my-promos" data-content="1">
                    <?php 

                        $boughtPromosQuery = "SELECT * FROM users_promos WHERE user_id=$currUserId and promo_is_used=0";
                        $result = mysqli_query($link, $boughtPromosQuery) or die("Ошибка выполнения запроса" . mysqli_error($link));
                        while ($res = mysqli_fetch_assoc($result)) {
                            $boughtPromos[] = $res['promo_id'];
                        }

                        if(mysqli_num_rows($result) == 0) {
                            echo "<div> Вы не покупали промокоды </div>";
                        } else {

                            for ($i = 0; $i < count($boughtPromos); $i++) {
                                $query = "SELECT fir.promo_id, category_id, subcategory, image, title, promocode, ending_date, price, likes_amount, bought_amount FROM (SELECT p.*, COUNT(l.promo_id) as likes_amount FROM promos p left join likes l on p.promo_id = l.promo_id GROUP BY p.promo_id) fir left JOIN (SELECT p.promo_id, COUNT(up.promo_id) as bought_amount FROM promos p left join users_promos up on p.promo_id = up.promo_id GROUP BY p.promo_id) sec on sec.promo_id=fir.promo_id WHERE fir.promo_id=$boughtPromos[$i]";
                                $result = mysqli_query($link, $query) or die("Ошибка выполнения запроса" . mysqli_error($link));
                                $card = mysqli_fetch_assoc($result);
                                ?>
                                <div class="card">
                                    <a href="promo.php?promoId=<?=@$card['promo_id']?>">
                                        <img src="/img/promos/<?=@$card['image']?>" alt="promo" class="promo-img">
                                        <div class="promo-info">
                                            <h3 class="promo-heading"><?=@$card['title']?></h3>
                                            <div class="promocode-info">Промокод: <span class="promocode"><?=@$card['promocode']?></span></div>
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
                                    <div class="delete-block" data-promo-id='<?=@$card['promo_id']?>' data-user-id='<?=@$currUserId?>'>
                                        <img src="/img/cross.svg" alt="delete" class="delete-image">
                                    </div>
                                </div>

                                <?php
                            }

                        }
                    ?>
                    </div>

                    <div class="content personal-data" data-content="2">    

                        <form class="change-personal-form change-password" method="POST">

                            <p class="password-state"></p>

                            <div class="box-input">
                                <label>Введите старый пароль</label>
                                <input class="input password" type="password" name="oldPassword" value="" require>        
                            </div>
                
                            <div class="box-input">
                                <label>Введите новый пароль</label>
                                <input class="input password" type="password" name="newPassword" value="" require>
                            </div>

                            <input type="submit" class="change-personal-btn" data-change='password' data-userid=<?=@$currUserId?> value="Подтвердить">
                        </form>

                    </div>

                    <div class="content fav-promos" data-content="3">

                    <?php 

                        if(count($likedPromos) == 0) {
                            echo "<div> Вы не оценивали промокоды </div>";
                        } else {

                            for ($i = 0; $i < count($likedPromos); $i++) {
                                $query = "SELECT fir.promo_id, category_id, subcategory, image, title, promocode, ending_date, price, likes_amount, bought_amount FROM (SELECT p.*, COUNT(l.promo_id) as likes_amount FROM promos p left join likes l on p.promo_id = l.promo_id GROUP BY p.promo_id) fir left JOIN (SELECT p.promo_id, COUNT(up.promo_id) as bought_amount FROM promos p left join users_promos up on p.promo_id = up.promo_id GROUP BY p.promo_id) sec on sec.promo_id=fir.promo_id WHERE fir.promo_id=$likedPromos[$i]";
                                $result = mysqli_query($link, $query) or die("Ошибка выполнения запроса" . mysqli_error($link));
                                $card = mysqli_fetch_assoc($result);
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

                    <!-- QUIZ -->

                    <div class="content top-up-balance" data-content="4">
                        <h3>Отвечай верно на вопрос — получай 0.5 BYN на счёт!</h3>
                        <div class="quiz-container">
                            <div id="question"></div>
                            <div class="options">
                                <div data-id="0" class="option option1"></div>
                                <div data-id="1" class="option option2"></div>
                                <div data-id="2" class="option option3"></div>
                                <div data-id="3" class="option option4"></div>
                            </div>
                            <div class="next-question-button">
                                <button id="btn-next">Далее</button>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </section>

    <?php require '../partials/footer.php' ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="/js/account.js"></script>
    
</body>
</html>