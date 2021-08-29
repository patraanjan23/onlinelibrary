<?php
require('includes/init.php');
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["logout"])) {
  session_unset();
  session_destroy();
  header("Location: home.php");
}
