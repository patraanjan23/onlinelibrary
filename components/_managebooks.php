<?php
require('_nodirectaccess.php');
require_once('includes/dbconnect.php');
require_once('includes/sqlhelper.php');

$formName = '';
$addBookData = array();
$delBookData = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add-books'])) {
        $formName = 'add-books';
        $bookObj = retrieveBook($_POST['title'], $_POST['author'], $_POST['isbn']);
        if ($bookObj !== null) {
            $title = $bookObj['title'];
            $author = $bookObj['author'];
            $isbn = $bookObj['isbn'];
            $isbn = addBook($conn, $title, $author, $isbn);
            if ($isbn !== null) {
                $addBookData['status'] = "book '$isbn' added successfully";
            } else {
                $addBookData['status'] = "error adding book to database";
            }
        } else {
            $addBookData['status'] = 'error in book data';
        }
    } elseif (isset($_POST['del-books'])) {
        $formName = 'del-books';
        $bookid = retrieveBookId($_POST['bookid']);
        if ($bookid !== null) {
            $delBookData['status'] = delBook($conn, $bookid);
        } else {
            $delBookData['status'] = 'error bookid format';
        }
    }
}

?>

<div>
    <h4>Add Books</h4>
    <form method="post">
        <input type="hidden" name="add-books">
        <input type="text" name="title" placeholder="Title">
        <input type="text" name="author" placeholder="Author">
        <input type="text" name="isbn" placeholder="ISBN">
        <input type="submit" value="Add Book">
        <p><?php if (isset($addBookData['status'])) echo $addBookData['status']; ?></p>
    </form>
</div>

<div>
    <h4>Delete Books</h4>
    <form method="post">
        <input type="hidden" name="del-books">
        <input type="text" name="bookid" placeholder="Book ID">
        <input type="submit" value="Delete Book">
        <p><?php if (isset($delBookData['status'])) echo $delBookData['status']; ?></p>
    </form>
</div>