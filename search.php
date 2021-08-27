<?php require('includes/init.php'); ?>

<?php
require_once('includes/sanitizer.php');
require_once('includes/dbconnect.php');

$search = $searchBy = "";
$result = null;

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $search = sanitize_input($_GET["search"]);
    $searchBy = sanitize_input($_GET["searchby"]);
    if (!empty($search) && !empty($searchBy)) {
        $sql = "SELECT `b`.`book_id` `book_id`, `isbn`, `title`, `author`, `category`, `edition`, `borrow_date`, `return_date`, `due_date` FROM `books` `b` LEFT JOIN `borrows` `bw` ON `b`.`book_id` = `bw`.`book_id` WHERE $searchBy LIKE '%$search%'";
        $result = $conn->query($sql);
    }
}

function show_table($result)
{
    if ($result !== null && $result->num_rows > 0) {
        echo '<table class="styled-table">
            <thead>
                <tr>
                    <th>book ID</th>
                    <th>ISBN</th>
                    <th>title</th>
                    <th>author</th>
                    <th>category</th>
                    <th>edition</th>
                    <th>available</th>
                </tr>
            </thead>
            <tbody>';
        while ($row = $result->fetch_assoc()) {
            // var_dump($row);
            $bookid = $row["book_id"];
            echo "<tr>";
            echo "<td>" . $row["book_id"] . "</td>";
            echo "<td>" . $row["isbn"] . "</td>";
            echo "<td><a href='book.php?id=$bookid'>" . $row["title"] . "</a></td>";
            echo "<td>" . $row["author"] . "</td>";
            echo "<td>" . $row["category"] . "</td>";
            echo "<td>" . $row["edition"] . "</td>";
            $borrowDate = $row["borrow_date"];
            $dueDate = $row["due_date"];
            $returnDate = $row["return_date"];
            $available =  $borrowDate . " " . $dueDate . " " . $returnDate;
            $available =  'Yes';
            if (!$borrowDate || $borrowDate && $returnDate && strtotime($returnDate) > strtotime($borrowDate)) {
                $available = '<a href="#">Yes</a>';
            } else {
                $available = 'No';
            }
            echo "<td>" . $available . "</td>";

            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo 'no result found!';
    }
}

?>

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
        <div class="container width90 center-top">
            <h3 class="left-pad">Search Result</h3>
            <?php show_table($result); ?>
        </div>
    </div>
    <?php require('components/_footer.php'); ?>
</body>

</html>