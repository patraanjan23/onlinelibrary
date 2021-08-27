<?php
require("_nodirectaccess.php");
require_once('includes/dbconnect.php');

$sql = "SELECT * FROM `books` LIMIT 4";
$result = $conn->query($sql);
// var_dump($result->fetch_assoc());

function echoBooks($title, $cover)
{
    echo '<div class="swiper-slide">' .
        '<img src="' . $cover . '" alt="' . $title . '">' .
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
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echoBooks($row['title'], $row['cover']);
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