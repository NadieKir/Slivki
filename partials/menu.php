<?php 
    include $_SERVER['DOCUMENT_ROOT']."/database/db.php"; 

    $query="SELECT * FROM categories";
    $result = mysqli_query($link, $query) or die("Ошибка выполнения запроса" . mysqli_error($link));
?>

<nav class="nav">
    <div class="container">
        <ul class="menu">
            <?php 
                for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                    $row = mysqli_fetch_row($result);
                    echo "<li><a href='/pages/category.php?category=$row[0]'>$row[1]</a></li>";
                }
            ?>
        </ul>
    </div>
</nav>