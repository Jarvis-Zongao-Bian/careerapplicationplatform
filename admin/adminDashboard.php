<?php
session_start();
require "../common.php";
if ($_SESSION['loggedin'] != TRUE or $_SESSION['role'] != "Admin") {
    gotoPage("'../logout.php'");
}

include "../templates/adminHeader.php";
echo 'Welcome! '.$_SESSION['userid']."<br>";
?>
    <h3>Admin Dashboard</h3>
    <ul>
        <li>
            <a href="index.php">Home</a>
        </li>
        <li>
            <a href="viewUser.php">View Users</a>
        </li>
        <li>
            <a href="viewLog.php">View System Activity</a>
        </li>
        <li>
            <a href="../logout.php">Logout</a>
        </li>
    </ul>
<?php include "../templates/footer.php"; ?>