// ЛАЙКИ

let likeBlocks = document.querySelectorAll('.like-block');

let promoId, userId;

likeBlocks.forEach(likeBlock => {
    likeBlock.addEventListener('click', () => {

        promoId = likeBlock.dataset.promoId;
        userId = likeBlock.dataset.userId;

        $.ajax({
            url: `../responses/setLike.php?promoId=${promoId}&userId=${userId}`,
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

// MODAL

const buyPromoBtn = document.querySelector('.buy-btn');
const buyPromoModal = document.querySelector('.buy-promo-modal-overlay');

buyPromoBtn.addEventListener('click', () => {
    promoId = buyPromoBtn.dataset.promoId;
    userId = buyPromoBtn.dataset.userId;

    if(userId == 'guest') {
        alert('Войдите, чтобы покупать промокоды');
    } else {
        buyPromoModal.classList.remove('hidden');

        if(buyPromoModal.firstElementChild.dataset.state == 'success') {
            $.ajax({
                url: `/responses/buyPromo.php?promoId=${promoId}&userId=${userId}`,
                success: function() {
                    buyPromoBtn.parentElement.innerHTML = '<div class="you-have-promo-message" >Промокод уже ваш</div>';
                }
            });
        }
    }
})

buyPromoModal.addEventListener('click', (e) => {
    if(e.target.classList.contains('buy-promo-modal-overlay')) {
        buyPromoModal.classList.add('hidden');
    }
})



