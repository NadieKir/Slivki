$(document).ready(function(){
    $('.owl-carousel').owlCarousel({
        center: false,
        items:5,
        loop:false,
        margin:54,
        autoWidth:true,
        autoplay: true,
        autoplayTimeout:7000,
        smartSpeed: 500,
        dots:true,
        mouseDrag: false,
        rewind: true
    });
});

// ЛАЙКИ

let likeBlocks = document.querySelectorAll('.like-block');

let promoId, userId;

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

