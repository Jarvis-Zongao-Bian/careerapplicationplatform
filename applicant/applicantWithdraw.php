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

if (isset($_POST['withdraw'])) {
    try {
        $userid = $_SESSION['userid'];

        $connection = new PDO($dsn, $username, $password, $options);
        $jobId= $_GET['id'];

        $sql = "DELETE FROM Application WHERE jobId = :jobid AND applicantId = :userid;
                UPDATE Offer SET status='Declined' WHERE jobId = :jobid AND applicantId = :userid";

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
        $sthLog->bindValue(':activity', "withdrew application for job #".$jobId);
        $sthLog->execute();
    } catch (PDOException $error) {
        echo $sqlLog . "<br>" . $error->getMessage();
    }
}

if (isset($_GET['id'])) {
    try {
        $connection = new PDO($dsn, $username, $password, $options);
        $jobId= $_GET['id'];

        $sql = "SELECT * FROM Job WHERE jobId = :id";
        $statement = $connection->prepare($sql);
        $statement->bindValue(':id', $jobId);
        $statement->execute();

        $job = $statement->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $error) {
        echo $sql."<br>".$error->getMessage();
    }
} else {
    echo "Something went wrong";
    exit;
}
?>

<?php include "../templates/applicantHeader.php"; ?>

<?php if (isset($_POST['withdraw']) && $statement) : ?>
    <?php echo "Job #".escape($_POST['jobId']); ?> successfully withdrawn.
<?php endif; ?>

    <h2>Withdraw the application</h2>

    <form method="post">
        <?php if($job): ?>
            <?php foreach ($job as $key => $value) : ?>
                <label for="<?php echo $key; ?>"><?php echo ucfirst($key); ?></label>
                <input type="text" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo escape($value); ?>" <?php echo ($key? 'readonly' : null); ?>>
            <?php endforeach; ?>
        <?php endif; ?>
        <input type="submit" name="withdraw" value="Withdraw">
    </form>


<a href="applicantDashboard.php">Back to dashboard</a>

<?php include "../templates/footer.php"; ?>