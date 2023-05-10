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

if (isset($_POST['offerOption']) and $_POST['offerOption'] != '') {
    $newStatus = $_POST['offerOption'];
    $jobID = $_GET['job'];
    $applicantID = $_GET['applicant'];
    $connection = new PDO($dsn, $username, $password, $options);

    try {// update table Offer and Application
        $conUpdate = new PDO($dsn, $username, $password, $options);
        $sqlUpdate = "UPDATE Offer SET status=:status WHERE jobId=:jobid AND applicantId=:applicantid;
                    UPDATE Application SET status=:appStatus WHERE jobId=:jobid AND applicantId=:applicantid";
        $sthUpdate = $conUpdate->prepare($sqlUpdate);
        $sthUpdate->bindValue(':status', $newStatus);
        $sthUpdate->bindValue(':appStatus', ($newStatus == 'Approved') ? 'Successful' : 'Unsuccessful');
        $sthUpdate->bindValue(':jobid', $jobID);
        $sthUpdate->bindValue(':applicantid', $applicantID);
        $sthUpdate->execute();
    } catch (PDOException $error) {
        echo $sqlUpdate . "<br>" . $error->getMessage();
    }

//    // if number of approved offer equals number of job opening, job post should be closed
//    // and no longer take new applications.
//    if ($_POST['offerOption']=='Approved') {
//        // get number of sent approval on the same job
//        $sqlCountApproval = "SELECT count(DISTINCT applicantId) FROM Offer WHERE jobId=:jobid AND status='Approved';";
//        $sthCountApproval = $connection->prepare($sqlCountApproval);
//        $sthCountApproval->bindValue(':jobid', $jobID);
//        $sthCountApproval->execute();
//        $numberOfApproval = $sthCountApproval->fetchColumn() + 0;
//
//        // get number of opening for this position
//        $sqlCountOpening = "SELECT numberOfOpening FROM Job WHERE jobId=:jobid";
//        $sthCountOpening = $connection->prepare($sqlCountOpening);
//        $sthCountOpening->bindValue(':jobid', $jobID);
//        $sthCountOpening->execute();
//        $numberOfOpening = $sthCountOpening->fetchColumn() + 0;
//
//        if ($numberOfApproval >= $numberOfOpening) {
//            // close this job
//            $sqlCloseJob = "UPDATE Job SET status='Closed' WHERE jobId=:jobId";
//            $sthCloseJob = $connection->prepare($sqlCloseJob);
//            $sthCloseJob->bindValue(':jobid', $jobID);
//            $sthCloseJob->execute();
//
//            // mark all other applications on this job as Unsuccessful
//            $sqlReject = "UPDATE Application SET status='Unsuccessful' WHERE jobId=:jobId AND status!='Successful';";
//            $sthReject = $connection->prepare($sqlReject);
//            $sthReject->bindValue(':jobid', $jobID);
//            $sthReject->execute();
//        }
//    }
}

try {
    $connection = new PDO($dsn, $username, $password, $options);
    $sql = "SELECT * FROM Offer WHERE employerId=:employerid;";
    $statement = $connection->prepare($sql);
    $statement->bindValue('employerid', $_SESSION['userid']);
    $statement->execute();
    $result = $statement->fetchAll();

} catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}
?>

<?php require "../templates/employerHeader.php"; ?>

<h2>Offer List</h2>
<table>
    <thead>
    <tr>
        <th>Job ID</th>
        <th>Applicant ID</th>
        <th>Offer Date</th>
        <th>Offer Status</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php if($result): ?>
        <?php foreach ($result as $row) : ?>
            <tr>
                <td><?php echo escape($row["jobId"]); ?></td>
                <td><?php echo escape($row["applicantId"]); ?></td>
                <td><?php echo escape($row["postDate"]); ?></td>
                <td><?php echo escape($row["status"]); ?></td>
                <td>
                    <form name="actions" method="post" action="viewOffer.php?job=<?php echo escape($row["jobId"]);?>&applicant=<?php echo escape($row["applicantId"])?>">
                        <select name="offerOption" id="offerOption">
                            <option value="" disabled selected>Select</option>
                            <?php if ($row['status'] == "Accepted"): ?>
                                <option value="Approved">Final Approve</option>
                            <?php elseif ($row['status'] == "Declined"): ?>
                                <option value="Voided">Void Offer</option>
                            <?php endif; ?>
                        </select>
                        <input type="submit" value="Submit">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>


<?php require "../templates/footer.php"; ?>

