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
require "../config.php";
require "../common.php";
if ($_SESSION['loggedin'] != TRUE or $_SESSION['role'] != 'Employer') {
    gotoPage("'../logout.php'");
}

if (isset($_POST['actionOption'])) {
    try {
        $newStatus = $_POST['actionOption'].'ed';
        $jobID = $_GET['job'];
        $applicantID = $_GET['applicant'];
        $connection = new PDO($dsn, $username, $password, $options);

        $sql = "UPDATE Application SET status = :status WHERE jobID=:jobid AND applicantID=:applicantid;
                DELETE FROM Offer WHERE jobId=:jobid AND applicantId=:applicantid;";
        if ($_POST['actionOption'] == 'Offer') {
            $sql = $sql."INSERT INTO Offer VALUES (:employerid, :jobid, :applicantid, :date, 'Active');";
        }
        $sql = $sql."INSERT INTO Log (userId, activity) VALUES (:employerid, :activity)";

        $statement = $connection->prepare($sql);
        $statement->bindValue(':status', $newStatus);
        $statement->bindValue(':jobid', $jobID);
        $statement->bindValue(':applicantid', $applicantID);
        $statement->bindValue('employerid', $_SESSION['userid']);
        $statement->bindValue(':date', date("Y-m-d"));
        $statement->bindValue(':activity', $newStatus." job #".$jobID." to applicant #".$applicantID);
        $statement->execute();
    } catch (PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}

gotoPage("'viewApplication.php'");
?>

<?php include "../templates/employerHeader.php";?>

<?php include "../templates/footer.php"; ?>
