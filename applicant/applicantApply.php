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
if ($_SESSION['loggedin'] != TRUE or $_SESSION['role'] != "Applicant") {
    gotoPage("'../logout.php'");
}
if (isset($_POST['apply'])) {
    try {
        $userid = $_SESSION['userid'];

        $connection = new PDO($dsn, $username, $password, $options);
        $jobId = $_GET['id'];

        $sql = "INSERT INTO Application (jobId, applicantId, appliedDate, status)
                VALUES (:id, :userid, :date, 'Applied')";

        $statement = $connection->prepare($sql);
        $statement->bindValue(':id', $jobId);
        $statement->bindValue(':userid', $userid);
        $statement->bindValue(':date', date("Y-m-d"));
        $statement->execute();
    } catch (PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }

    try {
        $conLog = new PDO($dsn, $username, $password, $options);
        $sqlLog = "INSERT INTO Log (userId, activity) VALUES (:userid, :activity)";
        $sthLog = $conLog->prepare($sqlLog);
        $sthLog->bindValue(':userid', $_SESSION['userid']);
        $sthLog->bindValue(':activity', "applied for job #" . $jobId);
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

<?php if (isset($_POST['apply']) && $statement) : ?>
    <?php echo "Job #".escape($_POST['jobId']); ?> successfully applied.
<?php endif; ?>

<div class = "up"><h2>Apply for Job</h2></div>
<?php if ($_SESSION['category']=="Basic" or ($_SESSION['category']=="Prime" and intval($_SESSION['jobApplied']) >= 5)): ?>
    <p>Your number of applied jobs has exceeded your account category limit! Please consider withdraw some applications or upgrade your account category.</p>
<?php else: ?>
    <form method="post">
        <?php if($job): ?>
            <?php foreach ($job as $key => $value) : ?>
                <label for="<?php echo $key; ?>"><?php echo ucwords(implode(' ',preg_split('/(?=[A-Z])/', $key))); ?></label>
                <input type="text" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo escape($value); ?>" <?php echo ($key? 'readonly' : null); ?>>
            <?php endforeach; ?>
        <?php endif; ?>
        <input type="submit" name="apply" value="Apply for this Job">
    </form>
<?php endif; ?>

<?php include "../templates/footer.php"; ?>