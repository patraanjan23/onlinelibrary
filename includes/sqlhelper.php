<?php
require('components/_nodirectaccess.php');
require_once('includes/sanitizer.php');

// only call after checking current session email is not being deleted
function deleteUser($conn, $email)
{
    $status = '';
    $sql = "DELETE FROM users WHERE email LIKE '$email'";
    $result = $conn->query($sql);
    if ($result) {
        $status = "user $email deleted";
    } else {
        $status = "failed to delete $email: user has borrow";
    }
    return $status;
}

function userExists($conn, $email)
{
    $sql = "SELECT email FROM users WHERE email LIKE '$email'";
    $result = $conn->query($sql);
    return ($result->num_rows > 0);
}

function retrieveEmail($postEmail)
{
    $emailObject = array();
    if (empty($postEmail)) {
        $emailObject['emailError'] = 'email empty';
        $emailObject['email'] = null;
    } else {
        $emailObject['email'] = sanitize_input($postEmail);
        if (!filter_var($emailObject['email'], FILTER_VALIDATE_EMAIL)) {
            $emailObject['emailError'] = 'invalid email format';
            $emailObject['email'] = null;
        }
    }
    return $emailObject;
}

function generate_password($len = 8)
{
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()";
    $password = substr(str_shuffle($chars), 0, $len);
    return $password;
}

function createUser($conn, $email)
{
    $password = generate_password();
    $passhash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO `users` (`password`, `email`) VALUES('$passhash', '$email')";
    $result = $conn->query($sql);
    if (!$result) {
        $password = null;
    }
    return $password;
}
