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

try {
    $userid = $_SESSION['userid'];
    $role = $_SESSION['role'];

    $connection = new PDO($dsn, $username, $password, $options);

    $sql = "SELECT A.*, P.employerId AS employerId FROM Application A NATURAL JOIN Post P WHERE applicantId = :userid";

    $statement = $connection->prepare($sql);
    $statement->bindValue(':userid', $userid);
    $statement->execute();

    $result = $statement->fetchAll();

} catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}

if (isset($_POST['submitAction'])){

    try {
        $userid = $_SESSION['userid'];

        $connection = new PDO($dsn, $username, $password, $options);
        $jobId= $_GET['job'];
        if ($_POST['actionOption'] == "Withdraw") {
            $sql = "DELETE FROM Application WHERE jobId = :jobid AND applicantId = :userid;";
        } elseif ($_POST['actionOption'] == "Accept") {
            $sql = "UPDATE Offer SET status='Accepted' WHERE jobId = :jobid AND applicantId = :userid;
                    UPDATE Application SET status='Offer Accepted' WHERE jobId = :jobid AND applicantId = :userid;";
        } elseif ($_POST['actionOption'] == "Decline") {
            $sql = "UPDATE Offer SET status='Declined' WHERE jobId = :jobid AND applicantId = :userid;
                    UPDATE Application SET status='Offer Declined' WHERE jobId = :jobid AND applicantId = :userid";
        }
        $statement = $connection->prepare($sql);
        $statement->bindValue(':jobid', $jobId);
        $statement->bindValue(':userid', $userid);
        $statement->execute();
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }

    try {
        $conLog = new PDO($dsn, $username, $password, $options);
        $sqlLog = "INSERT INTO Log (userId, activity) VALUES (:userid, :activity)";
        $sthLog = $conLog->prepare($sqlLog);
        $sthLog->bindValue(':userid', $_SESSION['userid']);
        $sthLog->bindValue(':activity', $_POST['actionOption']."d application for job #".$jobId);
        $sthLog->execute();
    } catch (PDOException $error) {
        echo $sqlLog . "<br>" . $error->getMessage();
    }
    gotoPage("'applicantMaintainAppliedJob.php'");
}
?>

<?php include "../templates/applicantHeader.php"; ?>
<div class = "row">
<div class = "up"><h2>Job List</h2></div>
<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Employer Id</th>
        <th>Applied Date</th>
        <th>Current Status</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($result): ?>
    <?php foreach ($result as $row) : ?>
        <tr>
            <td><?php echo escape($row["jobId"]); ?></td>
            <td><?php echo escape($row["employerId"]); ?></td>
            <td><?php echo escape($row["appliedDate"]); ?></td>
            <td><?php echo escape($row["status"]); ?></td>
            <td>
                <form name="actions" method="post" action="applicantMaintainAppliedJob.php?job=<?php echo escape($row["jobId"]);?>">
                    <select name="actionOption" id="actionOption">
                        <option value="" disabled selected>Select</option>
                        <?php if ($row['status'] == "Applied"): ?>
                            <option value="Withdraw">Withdraw</option>
                        <?php elseif ($row['status'] == "Offered"): ?>
                            <option value="Accept">Accept</option>
                            <option value="Decline">Decline</option>
                        <?php endif; ?>
                    </select>
                    <button type="submit" name="submitAction">Submit</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
</div>


<?php include "../templates/footer.php"; ?>