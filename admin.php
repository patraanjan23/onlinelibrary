<?php require('includes/init.php'); ?>

<?php
if (!isset($_SESSION['type']) || empty($_SESSION['type']) || $_SESSION['type'] !== 'admin') {
    die("log in as admin to access this page!");
}
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
        <div class="container padded rounded4 width80 center-top">
            <h2 class="left-pad">Admin Panel</h2>
            <?php require("components/_adminpanel.php"); ?>
        </div>
    </div>
    <?php require('components/_footer.php'); ?>
    <script src="scripts/adminpanel.js"></script>
</body>

</html>