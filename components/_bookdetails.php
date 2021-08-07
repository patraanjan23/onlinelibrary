<?php require('_nodirectaccess.php'); ?>

<?php
$na = '[not available]';
if (!isset($cover)) $cover = '#';
if (!isset($title)) $title = $na;
if (!isset($author)) $author = $na;
if (!isset($category)) $category = $na;
if (!isset($description)) $description = $na;
if (!isset($isbn)) $isbn = $na;
if (!isset($edition)) $edition = $na;
?>

<div class="bookdetails">
    <img src='<?php echo $cover; ?>' alt="cover" width="400px">
    <div>
        <h3>title</h3>
        <p style="text-transform: capitalize;"><?php echo $title; ?> </p>
    </div>
    <div>
        <h3>author</h3>
        <p style="text-transform: capitalize;"><?php echo $author; ?> </p>
    </div>
    <div>
        <h3>category</h3>
        <p style="text-transform: capitalize;"><?php echo $category; ?> </p>
    </div>
    <div>
        <h3>description</h3>
        <p><?php echo $description; ?> </p>
    </div>
    <div>
        <h3>ISBN</h3>
        <p><?php echo $isbn; ?> </p>
    </div>
    <div>
        <h3>edition</h3>
        <p><?php echo $edition; ?> </p>
    </div>
    <button>Borrow</button>
</div>