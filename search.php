<?php define('direct-access', TRUE); ?>

<?php
require_once('includes/sanitizer.php');
require_once('includes/dbconnect.php');

$search = $searchBy = "";
$result = null;

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $search = sanitize_input($_GET["search"]);
    $searchBy = sanitize_input($_GET["searchby"]);
    if (!empty($search) && !empty($searchBy)) {
        $sql = "SELECT * FROM `books` LEFT JOIN `borrows` ON `books`.`book_id` = `borrows`.`book_id` WHERE $searchBy LIKE '%$search%'";
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
            echo "<tr>";
            echo "<td>" . $row["book_id"] . "</td>";
            echo "<td>" . $row["isbn"] . "</td>";
            echo "<td>" . $row["title"] . "</td>";
            echo "<td>" . $row["author"] . "</td>";
            echo "<td>" . $row["category"] . "</td>";
            echo "<td>" . $row["edition"] . "</td>";
            $borrowDate = $row["borrow_date"];
            $dueDate = $row["due_date"];
            $returnDate = $row["return_date"];
            $available =  $borrowDate . " " . $dueDate . " " . $returnDate;
            $available =  'Yes';
            if ($borrowDate) {
                if ($returnDate && strtotime($returnDate) > strtotime($borrowDate)) {
                    $available = 'Yes';
                } else {
                    $available = 'No';
                }
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
        <?php show_table($result); ?>
        <!-- <?php echo isset($_REQUEST['search']) ? $_REQUEST['search'] : ""; ?>
        <br>
        <?php echo isset($_REQUEST['searchby']) ? $_REQUEST['searchby'] : ""; ?>
        <br> -->
    </div>
    <?php require('components/_footer.php'); ?>
</body>

</html>