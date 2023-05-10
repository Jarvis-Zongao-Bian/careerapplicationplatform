<?php
    session_start();
    require "../config.php";
    require "../common.php";
    if ($_SESSION['loggedin'] != TRUE or $_SESSION['role'] != "Employer") {
        gotoPage("'../logout.php'");
    }
    include "../templates/employerHeader.php";
    echo 'Welcome! '.$_SESSION['userid']."<br>";
    ?>
<h3>Employer Dashboard</h3>
<ul>
    <?php if ($_SESSION['accountStatus'] != 'Frozen' and $_SESSION['accountStatus'] != 'Deactivated'): ?>
        <li>
            <a href="postJob.php">Post Jobs</a>
        </li>
        <li>
            <a href="viewJob.php">View Job Posts</a>
        </li>
        <li>
            <a href="viewApplication.php">View Applications</a>
        </li>
        <li>
            <a href="viewOffer.php">View Offers</a>
        </li>
    <?php endif; ?>
    <li>
        <a href="viewAccount.php">My Account</a>
    </li>
    <li>
        <a href="contact.php">Contact Us</a>
    </li>
    <li>
        <a href="../logout.php">Logout</a>
    </li>
</ul>
<?php include "../templates/footer.php"; ?>
