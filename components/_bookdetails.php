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
if (!isset($bookavailable)) $bookavailable = false;
?>

<div class="bookdetails">
    <img src='<?php echo $cover; ?>' alt="cover" width="400px">
    <div class="bd-title">
        <h3>title</h3>
        <p style="text-transform: capitalize;"><?php echo $title; ?> </p>
    </div>
    <div class="bd-author">
        <h3>author</h3>
        <p style="text-transform: capitalize;"><?php echo $author; ?> </p>
    </div>
    <div class="bd-cat">
        <h3>category</h3>
        <p style="text-transform: capitalize;"><?php echo $category; ?> </p>
    </div>
    <div class="bd-desc">
        <h3>description</h3>
        <p><?php echo $description; ?> </p>
    </div>
    <div class="bd-isbn">
        <h3>ISBN</h3>
        <p><?php echo $isbn; ?> </p>
    </div>
    <div class="bd-edition">
        <h3>edition</h3>
        <p><?php echo $edition; ?> </p>
    </div>
    <button class="bd-borrow" id="btn-borrow" <?php echo $bookavailable ? "" : "disabled"; ?>><?php echo $bookavailable ? 'Borrow' : 'Unavailable'; ?></button>

</div>

<div class="modal" id="modal-borrow">
    <a href="" id="modal-close-btn">x</a>
    <?php if (empty($_SESSION['user_id']) || !isset($_SESSION['user_id'])) { ?>
        <p>login to get this book</p>
    <?php } else { ?>
        <h2>Request Details</h2>
        <h3>U<?php echo $_SESSION['user_id'] ?>B<?php echo $bookid ?></h3>
        <p>Provide the above info to an admin to assign the book.</p>
    <?php } ?>
</div>