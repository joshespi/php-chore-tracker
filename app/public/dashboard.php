<?php
$pageName = "Dashboard";
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}


include_once(__DIR__ . "/../includes/header.php");

//page content starts here
?>




Login success!
<a href="logout.php">Logout</a>


<?php
//page content ends here
include_once(__DIR__ . "/../includes/footer.php");
?>