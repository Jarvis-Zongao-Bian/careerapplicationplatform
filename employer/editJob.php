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
    if ($_SESSION['loggedin'] != TRUE or $_SESSION['role'] != "Employer") {
        gotoPage("'../logout.php'");
    }

if (isset($_POST['submitJob'])) {
    try {
        $connection = new PDO($dsn, $username, $password, $options);
        $job =[
            "jobId"        => $_POST['jobId'],
            "jobTitle" => $_POST['jobTitle'],
            "description"  => $_POST['description'],
            "numberOfOpening"     => $_POST['numberOfOpening'],
            "postDate"       => $_POST['postDate'],
            "category"  => $_POST['category'],
            "status"      => $_POST['status']
        ];

        $sql = "UPDATE Job
			SET jobId = :jobId,
			jobTitle = :jobTitle,
			description = :description,
			numberOfOpening = :numberOfOpening,
			postDate = :postDate,
			category = :category,
			status = :status
			WHERE jobId = :jobId";

        $statement = $connection->prepare($sql);
        $statement->execute($job);
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }

    try {
        $conLog = new PDO($dsn, $username, $password, $options);
        $sqlLog = "INSERT INTO Log (userId, activity) VALUES (:userid, :activity)";
        $sthLog = $conLog->prepare($sqlLog);
        $sthLog->bindValue(':userid', $_SESSION['userid']);
        $sthLog->bindValue(':activity', "edited job #".$_POST['jobId']);
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

<?php require "../templates/employerHeader.php"; ?>

<?php if (isset($_POST['submitJob']) && $statement) : ?>
    <?php echo "Job #".escape($_POST['jobId'])." is successfully updated.";?>
<?php endif; ?>

    <h2>Edit a job posting</h2>

    <form method="post">
    <?php if($job): ?>
        <?php foreach ($job as $key => $value) : ?>
            <label for="<?php echo $key; ?>"><?php echo ucfirst($key); ?></label>
            <input type="text" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo escape($value); ?>" <?php echo ($key == 'jobId' or $key == 'status') ? 'readonly' : null; ?>>
        <?php endforeach; ?>
    <?php endif; ?>
        <input type="submit" name="submitJob" value="Submit">
    </form>

    <a href="viewJob.php">Back to job listings</a>

<?php require "../templates/footer.php"; ?>