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

try {
    $connection = new PDO($dsn, $username, $password, $options);

    $sql = "SELECT A.* FROM Application A NATURAL JOIN Post P WHERE P.employerId=:employerid";
    $statement = $connection->prepare($sql);
    $statement->bindValue('employerid', $_SESSION['userid']);
    $statement->execute();
    $result = $statement->fetchAll();

} catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}
?>

<?php require "../templates/employerHeader.php"; ?>

    <h2>Application List</h2>
    <table>
        <thead>
        <tr>
            <th>Job ID</th>
            <th>Applicant ID</th>
            <th>Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php if($result): ?>
            <?php foreach ($result as $row) : ?>
                <tr>
                    <td><?php echo escape($row["jobId"]); ?></td>
                    <td><?php echo escape($row["applicantId"]); ?></td>
                    <td><?php echo escape($row["appliedDate"]); ?></td>
                    <td><?php echo escape($row["status"]); ?></td>
                    <td>
                        <form name="actions" method="post" action="actionApplication.php?job=<?php echo escape($row["jobId"]);?>&applicant=<?php echo escape($row["applicantId"])?>">
                            <select name="actionOption" id="actionOption">
                                <option value="" disabled selected>Select</option>
                                <option value="Offer">Offer</option>
                                <option value="Reject">Reject</option>
                            </select>
                            <button type="submit">Submit</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>


<?php require "../templates/footer.php"; ?>

