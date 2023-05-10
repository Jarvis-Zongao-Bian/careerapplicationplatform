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

if ($_GET['action'] == 'withdraw' and isset($_GET['id'])) {
    try {
        // delete a job posting if there is no existing offer
        // and log this activity
        $connection = new PDO($dsn, $username, $password, $options);
        $sqlWD = "UPDATE Application SET status='Rejected' WHERE jobId=:jobid;
                DELETE FROM Post WHERE jobId=:jobid;
                DELETE FROM Job WHERE jobId=:jobid;
                INSERT INTO Log (userId, activity) VALUES (:userid, :activity)";
        $sthWD = $connection->prepare($sqlWD);
        $sthWD->bindValue(':jobid', $_GET['id']);
        $sthWD->bindValue(':userid', $_SESSION['userid']);
        $sthWD->bindValue(':activity', "withdrawn job # ".$_GET['id']);
        $sthWD->execute();
    } catch(PDOException $error) {
        echo $sqlWD . "<br>" . $error->getMessage();
    }
}

try {
    $connection = new PDO($dsn, $username, $password, $options);
    // Get all jobs posted by the current logged in employer
    $sql = "SELECT J.* FROM Job J NATURAL JOIN Post P WHERE P.employerId=:employerid;";
    $statement = $connection->prepare($sql);
    $statement->bindValue(':employerid', $_SESSION['userid']);
    $statement->execute();
    $result = $statement->fetchAll();
} catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}
?>

<?php require "../templates/employerHeader.php"; ?>

<h2>Job List</h2>

<?php if ($sthWD) echo 'Job #'.$_GET['id'].' is successfully withdrawn.' ?>

<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Job Title</th>
        <th>Job Description</th>
        <th>Number of Opening</th>
        <th>Post Date</th>
        <th>Category</th>
        <th>Status</th>
        <th>Edit</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php if($result): ?>
        <?php foreach ($result as $row) : ?>
            <tr>
                <td><?php echo escape($row["jobId"]); ?></td>
                <td><?php echo escape($row["jobTitle"]); ?></td>
                <td><?php echo escape($row["description"]); ?></td>
                <td><?php echo escape($row["numberOfOpening"]); ?></td>
                <td><?php echo escape($row["postDate"]); ?></td>
                <td><?php echo escape($row["category"]); ?></td>
                <td><?php echo escape($row["status"]); ?> </td>
                <td><a href="editJob.php?id=<?php echo escape($row["jobId"]);?>">Edit</a></td>
                <?php
                    $sqlCount = "SELECT * FROM Offer WHERE jobId=:jobid";
                    $sthCount = $connection->prepare($sqlCount);
                    $sthCount->bindValue(':jobid', $row["jobId"]);
                    $sthCount->execute();
                    if ($sthCount->rowCount() == 0): ?>
                        <td><a href="viewJob.php?action=withdraw&id=<?php echo escape($row["jobId"]);?>">Withdraw</a></td>
                    <?php endif;?>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
            


<?php require "../templates/footer.php"; ?>
