<?php
if (!isset($GLOBALS['searchBy'])) {
    $GLOBALS['searchBy'] = 'title';
} else {
    $searchBy = $GLOBALS['searchBy'];
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
        <input type="search" name="search" id="searchbox" placeholder="Search Book by Title, Author or ISBN" required>
        <input type="submit" value="Search">
    </form>
</div>