<?php require('includes/init.php'); ?>

<?php
require_once('includes/dbconnect.php');
$userMsg = "";
$email = $password = $verifypassword = "";
$emailError = $passError = $verifyPassError = "";

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["verifypassword"])) {
        $verifyPassError = "please re-enter password";
    } else {
        $verifypassword = test_input($_POST["verifypassword"]);
        if ($password !== $verifypassword) {
            $verifyPassError = "password does not match";
        }
    }
}

function check_user_exists($conn, $email)
{
    $sql = "SELECT email FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return true;
    }
    return false;
}

$formOk = $GLOBALS['emailError'] . $GLOBALS['passError'] . $GLOBALS['verifyPassError'];
$formEmpty = $GLOBALS['email'] . $GLOBALS['password'] . $GLOBALS['verifypassword'];
if ($formOk === "" && $formEmpty !== "") {
    $email = $GLOBALS['email'];
    $password = $GLOBALS['password'];
    $passhash = password_hash($password, PASSWORD_DEFAULT);

    if (check_user_exists($conn, $email)) {
        $userMsg = 'user already exists';
    } else {
        $userMsg = 'user does not exist';
        $sql = "INSERT INTO library.users (password, name, email, acc_type, mobile, address) 
                                        VALUES('$passhash', NULL, '$email', 'normal', NULL, NULL)";
        $result = $conn->query($sql);
        if (!$result) {
            $userMsg = $userMsg . "<br>" . 'could not create user';
        } else {
            $userMsg = $userMsg . "<br>" . 'user ' . $email . ' created successfully';
            header('location: signin.php');
        }
    }
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
        <h2 class="padded center-top">Register</h2>
        <form name="signup" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-style center-top">
            <label for="email">email</label>
            <input type="email" name="email" value="<?php echo $email; ?>" required placeholder="yourname@example.com">
            <span class="error">* <?php echo $emailError; ?></span>

            <label for="password">password</label>
            <input type="password" name="password" value="<?php echo $password; ?>" required minlength="8" maxlength="32" placeholder="********">
            <span class="error"><?php echo $passError; ?></span>

            <label for="verifypassword">verify password</label>
            <input type="password" name="verifypassword" value="<?php echo $verifypassword; ?>" required minlength="8" maxlength="32" placeholder="********">
            <span class="error"><?php echo $verifyPassError; ?></span>

            <span></span>
            <input type="submit" value="Sign Up">
            <span></span>
        </form>

        <span class="padded"><?php echo $userMsg ?></span>
    </div>
    <?php require('components/_footer.php'); ?>
</body>

</html>