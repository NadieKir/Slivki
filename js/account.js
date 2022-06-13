let promoId, userId;

// TABS

const tabs = document.getElementById('tabs');
const content = document.querySelectorAll('.content');
const tabBtns = document.querySelectorAll('.tab-btn');

// СДЕЛАТЬ АКТИВНОЙ НУЖНУЮ ВКЛАДКУ

let paramsString = document.location.search; // EX: ?page=4&limit=10&sortby=desc  
let searchParams = new URLSearchParams(paramsString);
let activeTab = searchParams.get("activeTab");

if(activeTab == null) {
    content[0].classList.add('active');
    tabBtns[0].classList.add('active');
} else {
    content.forEach(con => {
        if(con.dataset.content == activeTab) {
            con.classList.add('active');
        }
    });

    tabBtns.forEach(tabBtn => {
        if(tabBtn.dataset.btn == activeTab) {
            tabBtn.classList.add('active');
        }
    });
}

    //

const changeClass = el => {
    if(el.classList.contains('tab-btn')) {
        for (let i = 0; i < tabs.children.length; i++) {
            tabs.children[i].classList.remove('active');
        }  
        el.classList.add('active');
    }
}

tabs.addEventListener('click', e => {
    const currTab = e.target.dataset.btn;
    changeClass(e.target);
    for(let i = 0; i < content.length; i++) {
        content[i].classList.remove('active');
        if(content[i].dataset.content === currTab) {
            content[i].classList.add('active');
        }
    }
})

// КУПЛЕННЫЕ ПРОМО

let deleteBlocks = document.querySelectorAll('.delete-block');
let boughtPromosBlock = document.querySelector('.my-promos');

addBoughtClickEvents();

function addBoughtClickEvents() {
    deleteBlocks.forEach(deleteBlock => {
        deleteBlock.addEventListener('click', () => {

        promoId = deleteBlock.dataset.promoId;
        userId = deleteBlock.dataset.userId;

        $.ajax({
            url: `/responses/deleteBoughtPromo.php?promoId=${promoId}&userId=${userId}`,
            success: function(cards) {
                if(cards == '0') {
                    boughtPromosBlock.innerHTML = "<div> Вы пока не покупали промокоды </div>";
                } else {
                    let parsedCards = JSON.parse(cards);
                    boughtPromosBlock.innerHTML = "";

                    parsedCards.forEach(card => {
                        boughtPromosBlock.innerHTML += card;
                    })

                    deleteBlocks = document.querySelectorAll('.delete-block');
                    addBoughtClickEvents();
                }
            }
        });
    })
})  
}

// LIKES

let likeBlocks = document.querySelectorAll('.like-block');
let favPromosBlock = document.querySelector('.fav-promos');

addClickEvents();

function addClickEvents() {
  likeBlocks.forEach(likeBlock => {
    likeBlock.addEventListener('click', () => {

        promoId = likeBlock.dataset.promoId;
        userId = likeBlock.dataset.userId;

        $.ajax({
            url: `/responses/deleteFavPromo.php?promoId=${promoId}&userId=${userId}`,
            success: function(cards) {
                if(cards == '0') {
                    favPromosBlock.innerHTML = "<div> Вы пока не оценивали промокоды </div>";
                } else {
                    let parsedCards = JSON.parse(cards);
                    favPromosBlock.innerHTML = "";

                    parsedCards.forEach(card => {
                        favPromosBlock.innerHTML += card;
                    })

                    likeBlocks = document.querySelectorAll('.like-block');
                    addClickEvents();
                }
            }
        });
    })
})  
}

// ИЗМЕНЕНИЕ ЛИЧНЫХ ДАННЫХ

const forms = document.querySelectorAll('.change-personal-form');
const passwordState = document.querySelector('.password-state');
let changingData;

forms.forEach(form => {
    form.addEventListener('submit', (e) => {
        e.preventDefault();
    })
})

const changePersonalDataBtns = document.querySelectorAll('.change-personal-btn');

changePersonalDataBtns.forEach(changePersonalDataBtn => {
    changePersonalDataBtn.addEventListener('click', () => {

        let whatToChange = changePersonalDataBtn.dataset.change;
        let currUserId = changePersonalDataBtn.dataset.userid;

        $.ajax({
            url: `/responses/changePersonalData.php?userId=${currUserId}&change=${whatToChange}`,
            type:     "POST",
            dataType: "html",
            data: $(".change-"+whatToChange).serialize(),
            success: function(json) {
                changingData = JSON.parse(json);

                if(changingData.isSuccess == true) {
                    passwordState.style.color = 'green';
                } else {
                    passwordState.style.color = 'red';
                }

                passwordState.innerHTML = changingData.message;
            }
        })
    })
})

// QUIZ

const option1 = document.querySelector('.option1'),
      option2 = document.querySelector('.option2'),
      option3 = document.querySelector('.option3'),
      option4 = document.querySelector('.option4');

const optionElements = document.querySelectorAll('.option');
const question = document.getElementById('question');
const btnNext = document.getElementById('btn-next');

let randomQuestionPath;
let indexOfQuestion;

let balance = document.getElementById('balance');
let userPhone = document.getElementById('phone');

let quizData;

$(window).on("load", function() {

    $.ajax({
        url: `/responses/getBalance.php?userPhone=${userPhone.innerHTML}`,
        success: function(gotBalance) {
            balance.innerHTML = gotBalance;
        }
    })

    $.ajax({
        url: '/responses/initQuiz.php',
        success: function(json) {
            quizData = JSON.parse(json);
            
            updateQuestionPath();
            randomQuestion();
        }
    });
});

function load() {
    question.innerHTML = quizData.questions[indexOfQuestion];

    option1.innerHTML = quizData.options[indexOfQuestion][0];
    option2.innerHTML = quizData.options[indexOfQuestion][1];
    option3.innerHTML = quizData.options[indexOfQuestion][2];
    option4.innerHTML = quizData.options[indexOfQuestion][3];
}

function shuffle(array) {
    array.sort(() => Math.random() - 0.5);
}

function updateQuestionPath() {
    randomQuestionPath = [];
    for (let i = 0; i < quizData.questions.length; i++) randomQuestionPath.push(i);
    shuffle(randomQuestionPath);
}

function randomQuestion() {
    indexOfQuestion = randomQuestionPath[0];
    randomQuestionPath.shift();
    load();

    if(randomQuestionPath.length == 0) {
        updateQuestionPath();
    }
};

function checkAnswer(el) {
    if(el.target.innerHTML == quizData.right_answers[indexOfQuestion]) {
        el.target.classList.add('correct');

        let userPhone = document.querySelector('#phone').innerHTML;

        $.ajax({
            url: `/responses/updateBalance.php?userPhone=${userPhone}`,
            success: function(newBalance) {
                balance.innerHTML = newBalance;
            }
        });

    } else {
        el.target.classList.add('wrong');
    }
    
    disabledOptions();
}

function disabledOptions() {
    optionElements.forEach(item => {
        item.classList.add('disabled');
        if (item.innerHTML == quizData.right_answers[indexOfQuestion]) {
            item.classList.add('correct');
        }
    })
}

function enableOptions() {
    optionElements.forEach(item => {
        item.classList.remove('disabled', 'correct', 'wrong');
    })
}

function validate() {
    if (!optionElements[0].classList.contains('disabled')) {
        alert('Выберите вариант ответа!');
    } else {
        enableOptions();
        randomQuestion();
    }
}

for (option of optionElements) {
    option.addEventListener('click', e => checkAnswer(e));
}

btnNext.addEventListener('click', () => {
    validate();
})








