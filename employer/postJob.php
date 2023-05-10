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

if (isset($_POST['submit'])) {

    try {
        $connection = new PDO($dsn, $username, $password, $options);
        // insert new job to Job
        $newJob = array(
            "jobTitle" => $_POST['jobTitle'],
            "description"  => $_POST['description'],
            "numberOfOpening"     => $_POST['numberOfOpening'],
            "postDate"  => date("Y-m-d"),
            "category"       => $_POST['category']
        );
        $sqlJob = sprintf("INSERT INTO %s (%s) VALUES (%s)",
            "Job",
            implode(", ", array_keys($newJob)),
            ":".implode(", :", array_keys($newJob)));
        $statement = $connection->prepare($sqlJob);
        $statement->execute($newJob);

        // get the auto-incremented jobId of the posted job
        $sth = $connection->prepare("SELECT MAX(DISTINCT jobId) FROM Job");
        $sth->execute();
        $jobId = $sth->fetchColumn();

        // insert into table Post
        $sqlPost = "INSERT INTO Post VALUES (:jobid, :employerid)";
        $sthPost = $connection->prepare($sqlPost);
        $sthPost->bindValue(':jobid', $jobId);
        $sthPost->bindValue(':employerid', $_SESSION['userid']);
        $sthPost->execute();
    } catch (PDOException $error) {
        echo $sqlJob . "<br>" . $error->getMessage();
    }

    try {
        $conLog = new PDO($dsn, $username, $password, $options);
        $sqlLog = "INSERT INTO Log (userId, activity) VALUES (:userid, :activity)";
        $sthLog = $connection->prepare($sqlLog);
        $sthLog->bindValue(':userid', $_SESSION['userid']);
        $sthLog->bindValue(':activity', "posted a new job #" . $jobId);
        $sthLog->execute();
    } catch (PDOException $error) {
        echo $sqlLog . "<br>" . $error->getMessage();
    }
}
?>

<?php
$_SESSION['category'] = "Prime";
try {
    $conCheck = new PDO($dsn, $username, $password, $options);
    $sqlCheck = "SELECT category FROM Employer WHERE userId=:userid";
    $sthCheck = $conCheck->prepare($sqlCheck);
    $sthCheck->bindValue(':userid', $_SESSION['userid']);
    $sthCheck->execute();
    $category = $sthCheck->fetchColumn();
    if ($category == "Basic") { $category="Prime";};
    $_SESSION['category'] = $category;
} catch (PDOException $error) {
    echo $sqlCheck . "<br>" . $error->getMessage();
}
try {
    $conPosted = new PDO($dsn, $username, $password, $options);
    $sqlPosted = "SELECT count(DISTINCT jobId) FROM Post WHERE employerId=:userid";
    $sthPosted = $conPosted->prepare($sqlPosted);
    $sthPosted->bindValue(':userid', $_SESSION['userid']);
    $sthPosted->execute();
    $jobPosted = $sthPosted->fetchColumn();
    $_SESSION['jobPosted'] = $jobPosted;
} catch (PDOException $error) {
    echo $sqlCheck . "<br>" . $error->getMessage();
}
    
?>

<?php include "../templates/employerHeader.php";?>
<?php if (isset($_POST['submit']) && $statement) : ?>
    <?php echo "The position ".escape($_POST['jobTitle'])." is successfully posted.";?>
<?php endif; ?>

<?php if ($_SESSION['category']=="Prime" and intval($_SESSION['jobPosted']) >= 5): ?>
    <p>Your number of posted jobs has exceeded your account category limit! Please consider delete some jobs or upgrade your account category.</p>
<?php else: ?>
    <form method="post">
        <label for="jobTitle">Job Title</label>
        <input type="text" name="jobTitle" id="jobTitle">
        <label for="description">Job Description</label>
        <input type="text" name="description" id="description">
        <label for="numberOfOpening">Number Of Opening</label>
        <input type="text" name="numberOfOpening" id="numberOfOpening">
        <label for="category">Category</label>
        <input type="text" name="category" id="category">
        <input type="submit" name="submit" value="Submit">
    </form>
<?php endif; ?>
<?php include "../templates/footer.php"; ?>