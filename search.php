<?php define('direct-access', TRUE); ?>

<!DOCTYPE html>
<html>

<head>
    <?php
    require('includes/styles.php');
    ?>
</head>

<body>
    <?php require('components/_header.php'); ?>
    <?php require('components/_search.php'); ?>
    <div class="spacer center">
        <?php echo isset($_REQUEST['search']) ? $_REQUEST['search'] : ""; ?>
        <br>
        <?php echo isset($_REQUEST['searchby']) ? $_REQUEST['searchby'] : ""; ?>
    </div>
    <?php require('components/_footer.php'); ?>
</body>

</html>