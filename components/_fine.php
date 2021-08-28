<?php
require('_nodirectaccess.php');
require_once('includes/dbconnect.php');
require_once('includes/sqlhelper.php');

$formName = '';
$chkFineData = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['chk-fine'])) {
        $formName = 'chk-fine';
        if (empty($_POST['userid'])) {
            $chkFineData['status'] = 'user id empty';
        } else {
            $userid = sanitize_input($_POST['userid']);
            $chkFineData['status'] = checkFine($conn, $userid);
        }
    } elseif (isset($_POST['adj-fine'])) {
        $formName = 'adj-fine';
        if (empty($_POST['userid']) || empty($_POST['fine'])) {
            $adjFineData['status'] = 'user id or fine empty';
        } else {
            $userid = sanitize_input($_POST['userid']);
            $fine = sanitize_input($_POST['fine']);
            $adjFineData['status'] = adjustFine($conn, $userid, $fine);
        }
    }
}
?>

<div>
    <h4>Check Fine</h4>
    <form method="post" class="form-style">
        <input type="hidden" name="chk-fine">
        <input type="text" name="userid" placeholder="User ID">
        <input type="submit" value="Check Fine">
    </form>
    <p><?php if (isset($chkFineData['status'])) echo $chkFineData['status']; ?></p>
</div>

<div>
    <h4>Adjust Fine Paid</h4>
    <form method="post" class="form-style">
        <input type="hidden" name="adj-fine">
        <input type="text" name="userid" placeholder="User ID">
        <input type="text" name="fine" placeholder="Fine Amount in Rs.">
        <input type="submit" value="Adjust Fine">
    </form>
    <p><?php if (isset($adjFineData['status'])) echo $adjFineData['status']; ?></p>
</div>