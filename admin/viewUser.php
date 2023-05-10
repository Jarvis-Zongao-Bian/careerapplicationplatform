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
if ($_SESSION['loggedin'] != TRUE or $_SESSION['role'] != "Admin") {
    gotoPage("'../logout.php'");
}

if (isset($_GET['action'])) {
    try {
        if ($_GET['action']=='deactivate') {
            $newStatus = "Deactivated";
        } elseif (intval($_GET['balance']) < 0){
            $newStatus = "Frozen";
        } else {
            $newStatus = "Active";
        }
        $conAction = new PDO($dsn, $username, $password, $options);
        $sqlAction = "UPDATE " . $_GET['role'] . " SET accountStatus=:accountStatus WHERE userid=:userid;";
        $sthAction = $conAction->prepare($sqlAction);
        $sthAction->bindValue(':accountStatus', $newStatus);
        $sthAction->bindValue(':userid', $_GET['userid']);
        $sthAction->execute();
    } catch (PDOException $error) {
        echo $sqlAction . "<br>" . $error->getMessage();
    }

    try {
        $conLog = new PDO($dsn, $username, $password, $options);
        $sqlLog = "INSERT INTO Log (userId, activity) VALUES ('Admin', :activity)";
        $sthLog = $conLog->prepare($sqlLog);
        $sthLog->bindValue(':activity', $_GET['action']."d user ".$_GET['userid']);
        $sthLog->execute();
    } catch (PDOException $error) {
        echo $sqlLog . "<br>" . $error->getMessage();
    }
}

try {
    $connection = new PDO($dsn, $username, $password, $options);
    $sql = "SELECT *, 'Employer' AS role FROM User NATURAL JOIN Employer UNION SELECT *, 'Applicant' AS role FROM User NATURAL JOIN Applicant;";
    $sth = $connection->query($sql, PDO::FETCH_ASSOC);
    $result = $sth->fetchAll();
} catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}
?>

<?php require "../templates/adminHeader.php"; ?>

<h2>User List</h2>

<?php //if ($sthWD) echo 'Job #'.$_GET['id'].' is successfully withdrawn.' ?>
<table>
    <thead>
    <tr>
        <?php if($result[0]): ?>
            <?php foreach ($result[0] as $key => $value): ?>
                <th><?php echo ucwords(implode(' ',preg_split('/(?=[A-Z])/', $key))); ?></th>
            <?php endforeach; ?>
        <?php endif; ?>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($result as $row) : ?>
        <tr>
            <?php foreach ($row as $key => $value): ?>
                <td><?php echo ucwords(implode(' ',preg_split('/(?=[A-Z])/', $value))); ?></td>
            <?php endforeach; ?>
            <td>
                <?php if ($row['accountStatus'] != 'Deactivated'): ?>
                    <a href="viewUser.php?action=deactivate&userid=<?php echo $row['userId'] ?>&role=<?php echo $row['role']?>&balance=<?php echo $row['balance']?>">Deactivate</a>
                <?php else: ?>
                    <a href="viewUser.php?action=activate&userid=<?php echo $row['userId'] ?>&role=<?php echo $row['role'] ?>&balance=<?php echo $row['balance']?>">Activate</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>


<?php require "../templates/footer.php"; ?>
