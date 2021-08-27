<?php require('includes/init.php'); ?>
<?php
require_once("includes/dbconnect.php");

$sql = "SELECT * FROM `books`";
$books = $conn->query($sql);

?>
<!DOCTYPE html>
<html>

<head>
    <title><?php require('components/_title.php'); ?></title>
    <?php require('includes/styles.php'); ?>
</head>

<body>
    <?php require('components/_header.php'); ?>
    <?php require('components/_search.php'); ?>
    <div class="spacer center">
        <div class="container width80">
            <h2 class="left-pad">Browse Books</h2>
            <?php require("components/_bookgrid.php"); ?>
        </div>
    </div>
    <?php require('components/_footer.php'); ?>
</body>

</html>