<?php require('includes/init.php'); ?>

<!DOCTYPE html>
<html>

<head>
    <title><?php require('components/_title.php'); ?></title>
    <?php require('includes/styles.php'); ?>
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
</head>

<body>
    <?php require('components/_header.php'); ?>
    <?php require('components/_search.php'); ?>
    <div class="spacer center">
        <div class="container">
            <h2 class="left-pad">Featured Books</h2>
            <?php require("components/_carousel.php"); ?>
            <?php require("components/_noticeboard.php"); ?>
        </div>
    </div>
    <?php require('components/_footer.php'); ?>
    <script src="scripts/bookcarousel.js"></script>
</body>

</html>