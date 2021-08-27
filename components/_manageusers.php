<?php require('_nodirectaccess.php'); ?>

<?php
require_once('includes/dbconnect.php');
require_once('includes/sqlhelper.php');

$formName = '';
$deleteFormData = array();
$createFormData = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete-user'])) {
        $formName = 'delete-user';
        $emailObj = retrieveEmail($_POST['email']);
        $email = $emailObj['email'];
        if ($email !== null) {
            if ($email === $_SESSION['email']) {
                $deleteFormData['status'] = "deleting current account is not allowed";
            } else {
                if (userExists($conn, $email)) {
                    $deleteFormData['status'] = deleteUser($conn, $email);
                } else {
                    $deleteFormData['status'] = 'user does not exist';
                }
            }
        } else {
            $deleteFormData['status'] = $emailObj['emailError'];
        }
    } elseif (isset($_POST['create-user'])) {
        $formName = 'create-user';
        $emailObj = retrieveEmail($_POST['email']);
        $email = $emailObj['email'];
        if ($email !== null) {
            if (userExists($conn, $email)) {
                $createFormData['status'] = "user $email already exists";
            } else {
                $password = createUser($conn, $email);
                $createFormData['status'] = "new user $email created. password is '$password'";
            }
        }
    }
}
?>

<div>
    <h4>Delete User</h4>
    <form method="post">
        <input type="hidden" name="delete-user">
        <input type="email" name="email" placeholder="Email of user to be deleted">
        <input type="submit" value="Delete User">
    </form>
    <p><?php if (isset($deleteFormData['status'])) echo $deleteFormData['status']; ?></p>
</div>

<div>
    <h4>Create New User</h4>
    <form method="post">
        <input type="hidden" name="create-user">
        <input type="email" name="email" placeholder="Email address of new user">
        <input type="submit" value="Create User">
    </form>
    <p><?php if (isset($createFormData['status'])) echo $createFormData['status']; ?></p>
</div>