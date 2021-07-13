<?php
    
?>

<html>

<body>
    <nav>
        <ul>
            <li><a href="page2.php">page 2</a></li>
        </ul>
    </nav>
    <h1>Form</h1>
    <form action="" method="POST">
        Name: <input type="text" name="name" id="name"><br>
        Email: <input type="text" name="email" id="email"><br>
        <input type="submit" value="Submit">
    </form>
    <h1>Response</h1>
    <?php
            echo $_REQUEST['name'] . $_REQUEST['email']
        ?>
</body>

</html>