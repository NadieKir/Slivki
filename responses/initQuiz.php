<?php 

include $_SERVER['DOCUMENT_ROOT']."/database/db.php"; 

// ЗАПОЛНЕНИЕ МАССИВОВ ИЗ БД

$questions = [];
$right_answers = [];
$wrong_answers = [];

$quizInfoQuery="SELECT question, right_answer, wrong_answers FROM quiz";
$quizInfoResult = mysqli_query($link, $quizInfoQuery) or die("Ошибка выполнения запроса" . mysqli_error($link));

while ($question = mysqli_fetch_assoc($quizInfoResult)) {
    $questions[] = $question['question'];
    $right_answers[] = $question['right_answer'];
    $wrong_answers[] = $question['wrong_answers'];
}

// СОЗДАНИЕ МАССИВА OPTIONS ИЗ ВЕРНЫХ И НЕВЕРНЫХ ОТВЕТОВ 

function explodeWrongAnswers($str) {
    return explode('@', $str);
}

$wrong_answers_arr = array_map('explodeWrongAnswers', $wrong_answers);

$options = [];

for($i = 0; $i < count($questions); $i++) {
    $options[$i] = $wrong_answers_arr[$i];
    $options[$i][] = $right_answers[$i];

    shuffle($options[$i]);
}

// СОЗДАНИЕ ОБЪЕКТА СО ВСЕМИ ДАННЫМИ ДЛЯ КВИЗА

class QuizData {
    public $questions;
    public $options;
    public $right_answers;
}

$quizData = new QuizData;

$quizData->questions = $questions;
$quizData->options = $options;
$quizData->right_answers= $right_answers;

echo json_encode($quizData);

?>