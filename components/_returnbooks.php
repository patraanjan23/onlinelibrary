<?php
require('_nodirectaccess.php');
require_once('includes/dbconnect.php');
require_once('includes/sqlhelper.php');

$formName = '';
$returnData = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['return-book'])) {
        $formName = 'return-book';
        if (empty($_POST['userid']) || empty($_POST['bookid']) || empty($_POST['retdate'])) {
            $returnData['status'] = 'all fields must be filled';
        } else {
            $userid = sanitize_input($_POST['userid']);
            $bookid = sanitize_input($_POST['bookid']);
            $retdate = sanitize_input($_POST['retdate']);

            // check if user has borrowed that book
            if (hasBorrowed($conn, $userid, $bookid)) {
                // assign book to the user
                $returnData['status'] = returnBook($conn, $userid, $bookid, $retdate);
            } else {
                $returnData['status'] = "book $bookid is not borrowed by user $userid";
            }
        }
    }
}
?>

<div>
    <h4>Return Books</h4>
    <form method="post" class="form-style">
        <input type="hidden" name="return-book">
        <input type="text" name="userid" placeholder="User ID">
        <input type="text" name="bookid" placeholder="Book ID">
        <label for="retdate">Return Date<input type="date" name="retdate" id="retdate"></label>
        <input type="submit" value="Return Book">
    </form>
    <p><?php if (isset($returnData['status'])) echo $returnData['status']; ?></p>
</div>