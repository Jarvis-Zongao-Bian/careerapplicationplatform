<?php
/**
 * @var string $host
 * @var string $username
 * @var string $password
 * @var string $dbname
 * @var string $dsn
 * @var array $options
 */

session_start();
require "../common.php";
require "../config.php";
if ($_SESSION['loggedin'] != TRUE or $_SESSION['role'] != "Applicant") {
    gotoPage("'../logout.php'");
}


if (isset($_POST['submit'])) {
    try {
        $connection = new PDO($dsn, $username, $password, $options);
        $sqlD = "DELETE FROM Application WHERE applicantId=:userid;
                DELETE FROM Offer WHERE applicantId=:userid;
                DELETE FROM Applicant WHERE userId=:userid;
                DELETE FROM Job WHERE jobId IN (SELECT jobId FROM Post WHERE employerId=:userid);
                DELETE FROM Post WHERE employerId=:userid;
                DELETE FROM Offer WHERE employerId=:userid;
                DELETE FROM Employer WHERE userId=:userid;
                DELETE FROM PayMethod WHERE accountNumber IN (SELECT accountNumber FROM PayWith WHERE userId=:userid);
                DELETE FROM PayWith WHERE userId=:userid;
                DELETE FROM User WHERE userId=:userid;";
        $statement = $connection->prepare($sqlD);
        $statement->bindValue(':userid', $_SESSION['userid']);
        $statement->execute();
    } catch(PDOException $error) {
        echo $sqlD . "<br>" . $error->getMessage();
    }

    try {
        $conLog = new PDO($dsn, $username, $password, $options);
        $sqlLog = "INSERT INTO Log (userId, activity) VALUES (:userid, :activity)";
        $sthLog = $conLog->prepare($sqlLog);
        $sthLog->bindValue(':userid', $_SESSION['userid']);
        $sthLog->bindValue(':activity', "deleted his/her profile");
        $sthLog->execute();
    } catch (PDOException $error) {
        echo $sqlLog . "<br>" . $error->getMessage();
    }
}
?>
<?php include "../templates/applicantHeader.php"; ?>
<h2>DO YOU REALLY WANT TO DELETE YOUR AWESOME PROFILE???!!!</h2>
<h2>IF THAT IS THE CASE, PRESS THE BUTTON BELOW!!!</h2>
<form method="post">
    <input type='submit' name='submit' value='DELETE MY PROFILE'>
</form>

<?php if (isset($_POST['submit']) && $statement) : ?>
    <?php echo "Your PROFILE is successfully DELETED." ?>
    <a href="../logout.php">Log out</a>
<?php endif; ?>

<?php require "../templates/footer.php"; ?>