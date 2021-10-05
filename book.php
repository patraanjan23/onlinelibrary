<?php require('includes/init.php'); ?>

<?php
require_once('includes/sanitizer.php');
require_once('includes/dbconnect.php');

$id = '';
$result = null;

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $id = sanitize_input($_GET["id"]);
    if (!empty($id)) {
        $sql = "SELECT `b`.`book_id` `book_id`, `isbn`, `title`, `author`, `category`, `edition`, `borrow_date`, `return_date`, `due_date`, `description`, `cover` FROM `books` `b` LEFT JOIN `borrows` `bw` ON `b`.`book_id` = `bw`.`book_id` WHERE `b`.`book_id` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $conn->quote($id));
        $stmt->execute();
        $result = $stmt->get_result();        
    }
}

function show_book($result)
{
    if ($result !== null && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // var_dump($row);
            $bookid = $row['book_id'];
            $cover = $row['cover'];
            $title = $row['title'];
            $author = $row['author'];
            $isbn = $row['isbn'];
            $description = $row['description'];
            $edition = $row['edition'];
            $category = $row['category'];
            $bookavailable = $row['borrow_date'] ? ($row['return_date'] ? (strtotime($row['return_date']) > strtotime($row['borrow_date']) ? true : false) : false) : true;
            require('components/_bookdetails.php');
        }
    } else {
        echo "<p>Book does not exist.</p>";
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
        <?php show_book($result); ?>
    </div>
    <?php require('components/_footer.php'); ?>
    <script src="scripts/book.js"></script>
</body>

</html>