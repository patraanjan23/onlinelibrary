<?php
require("_nodirectaccess.php");
require_once('includes/dbconnect.php');

$sql = "SELECT * FROM `books` LIMIT 4";
$result = $conn->query($sql);
// var_dump($result->fetch_assoc());

function echoBooks($bookid, $title, $cover)
{
    echo '<div class="swiper-slide">' .
        '<a href="book.php?id=' . $bookid . '"><img src="' . $cover . '" alt="' . $title . '"></a>' .
        '<h4>' . $title . '</h4>' .
        '</div>';
}
?>


<!-- Slider main container -->
<div class="swiper">
    <!-- Additional required wrapper -->
    <div class="swiper-wrapper">
        <!-- Slides -->
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echoBooks($row['book_id'], $row['title'], $row['cover']);
            }
        }
        ?>
        <!-- <div class="swiper-slide">Slide 1</div>
        <div class="swiper-slide">Slide 2</div>
        <div class="swiper-slide">Slide 3</div> -->
    </div>

    <!-- If we need navigation buttons -->
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>