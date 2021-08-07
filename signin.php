<?php require('includes/init.php'); ?>

<?php
require_once('includes/dbconnect.php');
$userMsg = "";
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
    $sql = "SELECT password FROM users WHERE email='$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $passhash = $result->fetch_assoc()['password'];
        $loggedin = password_verify($password, $passhash);
        if ($loggedin) {
            $_SESSION['loggedin'] = $loggedin;
            $_SESSION['email'] = $email;
            $userMsg = 'user logged in';
            header("location:/onlinelibrary/home.php");
        } else {
            $passError = "invalid password, try again";
        }
    } else {
        $userMsg = "user does not exist";
    }
} else {
    $userMsg = 'form invalid';
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
    <?php require('components/_header.php'); ?>
    <?php require('components/_search.php'); ?>
    <div class="spacer center">
        <h2 class="padded">Login</h2>
        <form name="signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="email">email</label>
            <input type="email" name="email" placeholder="yourname@example.com" value="<?php echo $email; ?>" required>
            <span class="error">* <?php echo $emailError; ?></span>

            <label for="password">password</label>
            <input type="password" name="password" placeholder="********" value="<?php echo $password; ?>" minlength="8" maxlength="32" required>
            <span class="error"><?php echo $passError; ?></span>

            <span></span>
            <input type="submit" value="Login">
            <span></span>
        </form>
        <span class="padded"><?php echo $userMsg ?></span>
    </div>
    <?php require('components/_footer.php'); ?>
</body>

</html>