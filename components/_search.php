<?php
require_once('includes/sanitizer.php');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET['search']) || empty($_GET['search']));
    else {
        $searchQ = sanitize_input($_GET['search']);
    }
    if (!isset($_GET['searchby'])) {
        $searchBy = 'title';
    } else {
        $searchBy = sanitize_input($_GET['searchby']);
    }
}
?>
<div class="search">
    <form action="search.php" method="get">
        <div class="center ">
            <div class="option">
                <label>
                    <input type="radio" name="searchby" id="title" value="title" <?php if (isset($searchBy) && $searchBy === 'title') echo 'checked'; ?>>
                    Title
                </label>
                <label>
                    <input type="radio" name="searchby" id="author" value="author" <?php if (isset($searchBy) && $searchBy === 'author') echo 'checked'; ?>>
                    Author
                </label>
                <label>
                    <input type="radio" name="searchby" id="isbn" value="isbn" <?php if (isset($searchBy) && $searchBy === 'isbn') echo 'checked'; ?>>
                    ISBN
                </label>
            </div>
        </div>
        <input type="search" name="search" id="searchbox" placeholder="Search Book by Title, Author or ISBN" value="<?php if (isset($searchQ) && !empty($searchQ)) echo $searchQ; ?>" required>
        <input type="submit" value="Search">
    </form>
</div>