// ПОДСВЕТИТЬ ПУНКТ МЕНЮ

let paramsString = document.location.search; // EX: ?page=4&limit=10&sortby=desc  
let searchParams = new URLSearchParams(paramsString);
let activeCategory = searchParams.get("category");

let allLi = document.querySelectorAll('.menu li a');

allLi.forEach(li => {
    if (li.getAttribute('href').slice(-1) == activeCategory) {
        li.style.textShadow = "0.5px 0 white";
    }
})

// ЛАЙКИ

let likeBlocks = document.querySelectorAll('.like-block');

let promoId, userId;

function addClickEvents() {
    likeBlocks.forEach(likeBlock => {
        likeBlock.addEventListener('click', () => {

            promoId = likeBlock.dataset.promoId;
            userId = likeBlock.dataset.userId;

            $.ajax({
                url: `/responses/setLike.php?promoId=${promoId}&userId=${userId}`,
                success: function(needChanging) {
                    
                if(+needChanging) {
                    if (likeBlock.firstElementChild.getAttribute('src') == '/img/like.svg') {
                        likeBlock.firstElementChild.setAttribute('src', '/img/liked.svg');
                        likeBlock.lastElementChild.innerHTML = `${parseInt(likeBlock.lastElementChild.innerHTML) + 1} `;
                    } else {
                        likeBlock.firstElementChild.setAttribute('src', '/img/like.svg');
                        likeBlock.lastElementChild.innerHTML = `${likeBlock.lastElementChild.innerHTML - 1} `;
                    }
                } else {
                    alert('Войдите, чтобы оценивать промокоды');
                }

                }
            });
        })
    })  
}

addClickEvents();

// ФИЛЬТРАЦИЯ И СОРТИРОВКА

let allFilterBtns = document.querySelectorAll('.filter-btns button');
let allSortBtns = document.querySelectorAll('.sort-btns button');
let allBtns = document.querySelectorAll('.btns-wrapper button');

let queryResultBlock = document.querySelector(".category-query-result");

let sortBy;
let category;
let subcategory;

allBtns.forEach(btn => {
    btn.addEventListener('click', (e) => {

        // ИЗМЕНИТЬ АКТИВНОСТЬ КНОПКИ

        if(btn.dataset.sortby) {
            allSortBtns.forEach(sortBtn => {
                sortBtn.classList.remove('active-sort-btn');
            })
            
            e.target.classList.add('active-sort-btn');
        } else {
            allFilterBtns.forEach(filterBtn => {
                filterBtn.classList.remove('active-filter-btn');
            })
            e.target.classList.add('active-filter-btn');
        }

        // СФОРМИРОВАТЬ ПАРАМЕТРЫ ЗАПРОСА

        if(btn.dataset.sortby) {
            sortBy = btn.dataset.sortby;
            category = document.querySelector('.filter-btns button.active-filter-btn').dataset.category;
            subcategory = document.querySelector('.filter-btns button.active-filter-btn').dataset.subcategory;
        } else {
            sortBy = document.querySelector('.sort-btns button.active-sort-btn').dataset.sortby;
            category = btn.dataset.category;
            subcategory = btn.dataset.subcategory;
        }

        // ЗАПРОС

        userId = likeBlocks[0].dataset.userId;

        $.ajax({
            url: `/responses/filterPromos.php?category=${category}&subcategory=${subcategory}&sortby=${sortBy}&userId=${userId}`,
            success: function(cards) {
                let parsedCards = JSON.parse(cards);
                queryResultBlock.innerHTML = "";

                parsedCards.forEach(card => {
                    queryResultBlock.innerHTML += card; 

                    likeBlocks = document.querySelectorAll('.like-block')
                    addClickEvents();
                })
            }
        }); 

    })
})






