// РЕАЛИЗАЦИЯ ТАБОВ

$(".main-form").on("click", ".tab", function () {
    $(".main-form").find(".active").removeClass("active");

    $(this).addClass("active");
    $(".tab-form").eq($(this).index()).addClass("active");
});

// ОСТАТЬСЯ НА ВКЛАДКЕ РЕГИСТАРЦИЯ ПРИ ОТПРАВКЕ ФОРМЫ, 
// НЕПРОШЕДШЕЙ ВАЛИДАЦИЮ

if (isRegBtnPressed) {
    $(".main-form").find(".active").removeClass("active");
    $(".registration").addClass("active");
    $(".tab-form").eq($(".registration").index()).addClass("active");

    isRegBtnPressed = false;
}


