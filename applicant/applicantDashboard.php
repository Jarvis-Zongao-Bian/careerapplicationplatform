<?php
session_start();
require "../common.php";
if ($_SESSION['loggedin'] != TRUE or $_SESSION['role'] != "Applicant") {
    gotoPage("'../logout.php'");
}
include "../templates/applicantHeader.php";
echo 'Welcome! '.$_SESSION['userid']."<br>";
?>



<!-- The flexible grid (content) -->
<div class="row">
  <div class="side" href="applicantProfile.php">
  </div>
  <div class="main">
  </div>
</div>


<?php include "../templates/footer.php"; ?>