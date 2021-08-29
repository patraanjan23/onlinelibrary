<?php
require("_nodirectaccess.php");

function book($bookid, $title, $cover)
{
    echo '' .
        "<div class=\"book has-shadow rounded4\">" .
        "    <a href=\"book.php?id=$bookid\"><img src=\"$cover\" alt=\"cover\" ></a>" .
        "    <p>$title</p>" .
        "</div>";
}

function bookGrid($results)
{
    if ($results !== null && $results->num_rows > 0) {
        while ($row = $results->fetch_assoc()) {
            book($row['book_id'], $row['title'], $row['cover']);
        }
    }
}

if (!isset($books)) $books = null;

?>

<div class="bookgrid">
    <?php bookGrid($books); ?>
</div>