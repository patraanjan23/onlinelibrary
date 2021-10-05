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
    $sql = "SELECT `id`, `email`, `password`, `acc_type` FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $conn->quote($email));
    $stmt->execute();
    $result = $stmt->get_result();     
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $passhash = $row['password'];
        $loggedin = password_verify($password, $passhash);
        if ($loggedin) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['type'] = $row['acc_type'];
            $userMsg = 'user logged in';
            header("location: index.php");
        } else {
            $passError = "invalid password, try again";
        }
    } else {
        $userMsg = "user does not exist";
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
        <h2 class="padded center-top">Login</h2>
        <form name="signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-style center-top">
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