<?php
require('_nodirectaccess.php');
require_once("includes/sanitizer.php");
require_once("includes/sqlhelper.php");

$formName = '';
$assignData = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['assign-book'])) {
        $formName = 'assign-book';
        if (empty($_POST['userid']) || empty($_POST['bookid']) || empty($_POST['bordate']) || empty($_POST['duedate'])) {
            $assignData['status'] = 'all fields must be filled';
        } else {
            $userid = sanitize_input($_POST['userid']);
            $bookid = sanitize_input($_POST['bookid']);
            $bordate = sanitize_input($_POST['bordate']);
            $duedate = sanitize_input($_POST['duedate']);
            if ($duedate <= $bordate) {
                $assignData['status'] = 'due date cannot be same or earlier than borrow date';
            } else {
                // check book is available
                if (isBookAvailable($conn, $bookid)) {
                    // assign book to the user
                    $assignData['status'] = assignBook($conn, $userid, $bookid, $bordate, $duedate);
                } else {
                    $assignData['status'] = "book $bookid is not available";
                }
            }
        }
    }
}

?>

<div>
    <h4>Assign Books</h4>
    <form method="post" class="form-style">
        <input type="hidden" name="assign-book">
        <input type="text" name="userid" placeholder="User ID">
        <input type="text" name="bookid" placeholder="Book ID">
        <label for="bordate">Borrow Date <input type="date" name="bordate" id="bordate"></label>
        <label for="duedate">Due Date <input type="date" name="duedate" id="duedate"></label>
        <input type="submit" value="Assign Book">
    </form>
    <p><?php if (isset($assignData['status'])) echo $assignData['status']; ?></p>
</div>