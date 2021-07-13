<!DOCTYPE html>
<?php

$formOk = true;
$name = $email = $gender = $comment = $website = "";
$nameErr = $emailErr = $genderErr = $commentErr = $websiteErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
        $nameErr = "name is required";
        $formOk = false;
    } else {
        $name = test_input($_POST["name"]);
        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            $nameErr = "only letters and whitespace allowed";
            $formOk = false;
        }
    }

    if (empty($_POST["email"])) {
        $emailErr = "email is required";
        $formOk = false;
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "invalid email address";
            $formOk = false;
        }
    }

    if (empty($_POST["website"])) {
        $website = "";
    } else {
        $website = test_input($_POST["website"]);
        if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $website)) {
            $websiteErr = "Invalid URL";
            $formOk = false;
        }
    }

    if (empty($_POST["comment"])) {
        $comment = "";
    } else {
        $comment = test_input($_POST["comment"]);
    }

    if (empty($_POST["gender"])) {
        $genderErr = "gender is required";
        $formOk = false;
    } else {
        $gender = test_input($_POST["gender"]);
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

<html>

<head>
    <style>
        * {
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            overflow: hidden;
        }

        .error {
            color: red;
        }

        form {
            max-width: 100%;
            background: lightpink;
            padding: 1rem;
            display: grid;
            grid-template-columns: 0.25fr 1fr 0.25fr;
        }

        form * {
            padding: 0.25rem;
            resize: none;
        }
    </style>
</head>

<body>
    <h1>Page 2</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

        Name: <input type="text" name="name" value="<?php echo $name; ?>"><span class="error"> *
            <?php echo $nameErr; ?></span><br>

        E-mail: <input type="text" name="email" value="<?php echo $email; ?>"><span class="error"> *
            <?php echo $emailErr; ?></span><br>

        Website: <input type="text" name="website" value="<?php echo $website; ?>"><span class="error"><?php echo $websiteErr; ?></span><br>

        Comment: <textarea name="comment" rows="5" cols="40"><?php echo $comment; ?></textarea><span></span><br>

        Gender:
        <div>
            <input type="radio" name="gender" <?php if (isset($gender) && $gender == "female") {
                                                    echo "checked";
                                                } ?> value="female">Female
            <input type="radio" name="gender" <?php if (isset($gender) && $gender == "male") {
                                                    echo "checked";
                                                } ?> value="male">Male
            <input type="radio" name="gender" <?php if (isset($gender) && $gender == "other") {
                                                    echo "checked";
                                                } ?> value="other">Other
        </div>
        <span class="error"> * <?php echo $genderErr; ?></span>
        <br>

        <input type="submit" value="Comment">
    </form>

</body>

</html>

<?php
// echo $name;
// echo "<br>";
// echo $email;
// echo "<br>";
// echo $website;
// echo "<br>";
// echo $comment;
// echo "<br>";
// echo $gender;
// echo "<br>";
echo $formOk ? "valid" : "invalid";
?>

<br>

<?php
$servername = "localhost";
$dbname = $username = "testuser";
$password = "A/.9EY6lBcU1N/h-";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully <br>";

$sql = "INSERT INTO comments (name, email, website, comment, gender) VALUES ('$name', '$email', '$website', '$comment', '$gender')";
if ($formOk) {
    if ($conn->query($sql) === true) {
        echo "new record created successfully";
    } else {
        echo "Error: " .  $conn->errno . " " . $conn->error;
    }
}

$conn->close();
?>