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
    if ($_SESSION['loggedin'] != TRUE) {
        gotoPage("'../logout.php'");
    }

try {
        // delete a job posting if there is no existing offer
        // and log this activity
        $connection = new PDO($dsn, $username, $password, $options);
        $sql = "SELECT name, email, phone FROM User WHERE userId=:userid";
        $sth = $connection->prepare($sql);
        $sth->bindValue(':userid', $_SESSION['userid']);
        $sth->execute();
        $result = $sth->fetch();
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
?>

<?php require "../templates/employerHeader.php"; ?>

    <h2>Contact Us</h2>

    <table>
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($result): ?>
            <tr>
                <td><?php echo escape($result["name"]); ?></td>
                <td><?php echo escape($result["email"]); ?></td>
                <td><?php echo escape($result["phone"]); ?></td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <a href="employerDashboard.php">Back to dashboard</a>

<?php require "../templates/footer.php"; ?>