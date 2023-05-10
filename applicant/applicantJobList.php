<?php
session_start();
require "../common.php";
require "../config.php";
if ($_SESSION['loggedin'] != TRUE or $_SESSION['role'] != "Applicant") {
    gotoPage("'../logout.php'");
}
?>

<?php
/**
 * @var string $host
 * @var string $username
 * @var string $password
 * @var string $dbname
 * @var string $dsn
 * @var array $options
 */

try {
    $userid = $_SESSION['userid'];
    $role = $_SESSION['role'];
    $connection = new PDO($dsn, $username, $password, $options);

    $sql = "SELECT J.*, P.employerId as employerId FROM Job J NATURAL JOIN Post P";

    $statement = $connection->prepare($sql);
    $statement->execute();

    $result = $statement->fetchAll();

} catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}
?>

<?php
$_SESSION['category'] = "Prime";
try {
    $conCheck = new PDO($dsn, $username, $password, $options);
    $sqlCheck = "SELECT category FROM Applicant WHERE userId=:userid";
    $sthCheck = $conCheck->prepare($sqlCheck);
    $sthCheck->bindValue(':userid', $_SESSION['userid']);
    $sthCheck->execute();
    $category = $sthCheck->fetchColumn();
    $_SESSION['category'] = $category;
} catch (PDOException $error) {
    echo $sqlCheck . "<br>" . $error->getMessage();
}
try {
    $conApplied = new PDO($dsn, $username, $password, $options);
    $sqlApplied = "SELECT count(DISTINCT jobId) FROM Application WHERE applicantId=:userid";
    $sthApplied = $conApplied->prepare($sqlApplied);
    $sthApplied->bindValue(':userid', $_SESSION['userid']);
    $sthApplied->execute();
    $jobApplied = $sthApplied->fetchColumn();
    $_SESSION['jobApplied'] = $jobApplied;
} catch (PDOException $error) {
    echo $sqlCheck . "<br>" . $error->getMessage();
}

?>

<?php include "../templates/applicantHeader.php"; ?>
<div class="row">
<div class="up">
<h2>Job List</h2>
<?php if ($_SESSION['category'] == 'Basic'): ?>
    <p> Your current account category is Basic and you won't be able to apply for jobs. Please consider upgrade.</p>
<?php endif; ?>
</div>
<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Job Title</th>
        <th>Employer ID</th>
        <th>Job Description</th>
        <th>Number of Opening</th>
        <th>Post Date</th>
        <th>Category</th>
        <th>Status</th>
        <th>Apply</th>
    </tr>
    </thead>
    <tbody>
    <?php if($result): ?>
        <?php foreach ($result as $row) : ?>
            <tr>
                <td><?php echo escape($row["jobId"]); ?></td>
                <td><?php echo escape($row["jobTitle"]); ?></td>
                <td><?php echo escape($row["employerId"]); ?></td>
                <td><?php echo escape($row["description"]); ?></td>
                <td><?php echo escape($row["numberOfOpening"]); ?></td>
                <td><?php echo escape($row["postDate"]); ?></td>
                <td><?php echo escape($row["category"]); ?></td>
                <td><?php echo escape($row["status"]); ?> </td>
                <?php if ($_SESSION['category'] != 'Basic'): ?>
                    <td><a href="applicantApply.php?id=<?php echo escape($row["jobId"]); ?>">Apply for Job</a>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
</div>

<?php include "../templates/footer.php"; ?>