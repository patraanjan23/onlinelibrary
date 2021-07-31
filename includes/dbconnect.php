<?php require('components/_nodirectaccess.php'); ?>
<?php
function db_connect()
{
    static $connection;

    if (!isset($connection)) {
        $config = parse_ini_file('../config.ini');
        $connection = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    }

    return $connection;
}

$conn = db_connect();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
