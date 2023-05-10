<?php
session_start();
require "../config.php";
require "../common.php";
if ($_SESSION['loggedin'] != TRUE) {
    gotoPage("'../logout.php'");
}
include "../templates/employerHeader.php";
echo 'Welcome! ' . $_SESSION['userid'] . "<br>";
echo 'You are logged in as an ' . $_SESSION['role'] . "<br>";
?>
    <h2>My Account</h2>
    <ul>
        <li>
            <a href="personalInfo.php">Personal Information</a>
        </li>
        <li>
            <a href="accountInfo.php">Account Information</a>
        </li>
        <li>
            <a href="payInfo.php">Payment Information</a>
        </li>
        <?php if ($_SESSION['role'] == 'Employer'): ?>
            <li>
                <a href='viewJob.php'>Posted Jobs</a>
            </li>
        <?php endif; ?>
        <?php if ($_SESSION['role'] == 'Applicant'): ?>
            <li>
                <a href='viewApplication.php'>Applied Jobs</a>
            </li>
            <li>
                <a href='viewOffer.php'>Received Offers</a>
            </li>
        <?php endif; ?>
        <li>
            <a href="applicant/applicantProfileDelete.php">Delete Profile</a>
        </li>
    </ul>

    <?php
    if ($_SESSION['role'] == 'Employer') {
        echo "<a href='employerDashboard.php'>Bash to Dashboard</a>";
    }
    ?>
    <?php
    if ($_SESSION['role'] == 'Applicant') {
        echo "<a href='applicant/applicantDashboard.php'>Bash to Dashboard</a>";
    }
    ?>
<?php include "../templates/footer.php"; ?>