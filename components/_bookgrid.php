<?php
require("_nodirectaccess.php");

function book($title, $cover)
{
    echo '' .
        "<div class=\"book has-shadow rounded4\">" .
        "    <img src=\"$cover\" alt=\"cover\" >" .
        "    <p>$title</p>" .
        "</div>";
}

function bookGrid($results)
{
    if ($results !== null && $results->num_rows > 0) {
        while ($row = $results->fetch_assoc()) {
            book($row['title'], $row['cover']);
        }
    }
}

if (!isset($books)) $books = null;

?>

<div class="bookgrid">
    <?php bookGrid($books); ?>
</div>