<?php
require_once('includes/dbconnect.php');
$email = $password = "";
$emailError = $passError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["email"])) {
        $emailError = "email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailError = "invalid email address";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["password"])) {
        $passError = "password is required";
    } else {
        $password = test_input($_POST["password"]);
        if (strlen($password) < 8) {
            $passError = "password length must be between 8-32";
        }
    }
}

$formOk = $GLOBALS['emailError'] . $GLOBALS['passError'];
$formEmpty = $email . $password;
if ($formOk === "" && $formEmpty !== "") {
    session_start();
    $sql = "SELECT password FROM users WHERE email='$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $passhash = $result->fetch_assoc()['password'];
        $loggedin = password_verify($password, $passhash);
        if ($loggedin) {
            $_SESSION['loggedin'] = $loggedin;
            $_SESSION['email'] = $email;
            echo 'user logged in';
            session_unset();
            session_destroy();
        } else {
            $passError = "invalid password, try again";
        }
    }
} else {
    echo "form invalid";
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html>

<head>
    <?php require('includes/styles.php'); ?>
</head>

<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="email">email</label><input type="text" name="email" value="<?php echo $email; ?>"><span class="error">* <?php echo $emailError; ?></span>
        <label for="password">password</label><input type="password" name="password" value="<?php echo $password; ?>"><span class="error"><?php echo $passError; ?></span>
        <span></span><input type="submit" value="Login"><span></span>
    </form>
</body>

</html>