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
    $connection = new PDO($dsn, $username, $password, $options);
    $userid = $_SESSION['userid'];

    $sql = "SELECT pm.accountNumber AS accountNumber, pm.payType AS payType, pm.expDate AS expDate, 
                pm.CVN AS CVN, pw.isDefault AS isDefault
            FROM  PayMethod pm NATURAL JOIN PayWith pw
            WHERE pw.userId=:userid";

    $statement = $connection->prepare($sql);
    $statement->bindValue(':userid', $userid);
    $statement->execute();

    $result = $statement->fetchAll();

} catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}

if (isset($_POST['selectPMAction'])) {
    if ($_POST['selectPMAction'] == 'makeDefault') {
        try {
            $sqlMD = "UPDATE PayWith SET isDefault=0 WHERE userId=:userid;
                                UPDATE PayWith SET isDefault=1
                                WHERE userId=:userid AND accountNumber=:acc AND payType=:payType;";
            $statementMD = $connection->prepare($sqlMD);
            $statementMD->bindValue(':userid', $userid);
            $statementMD->bindValue(':acc', $_GET['acc']);
            $statementMD->bindValue(':payType', $_GET['payType']);
            $statementMD->execute();
        } catch (PDOException $error) {
            echo $sqlMD . "<br>" . $error->getMessage();
        }

        try {
            $conLog = new PDO($dsn, $username, $password, $options);
            $sqlLog = "INSERT INTO Log (userId, activity) VALUES (:userid, :activity)";
            $sthLog = $conLog->prepare($sqlLog);
            $sthLog->bindValue(':userid', $_SESSION['userid']);
            $sthLog->bindValue(':activity', "changed default payment method to Account #".$_GET['acc']);
            $sthLog->execute();
        } catch (PDOException $error) {
            echo $sqlLog . "<br>" . $error->getMessage();
        }
    } elseif ($_POST['selectPMAction'] == 'delete') {
        try {
            $sqlDelete = "DELETE FROM PayWith
                        WHERE userId=:userid AND accountNumber=:acc AND payType=:payType;
                        DELETE FROM PayMethod
                        WHERE accountNumber=:acc AND payType=:payType;";
            $statementDelete = $connection->prepare($sqlDelete);
            $statementDelete->bindValue(':userid', $userid);
            $statementDelete->bindValue(':acc', $_GET['acc']);
            $statementDelete->bindValue(':payType', $_GET['payType']);
            $statementDelete->execute();
        } catch (PDOException $error) {
            echo $sqlDelete . "<br>" . $error->getMessage();
        }

        try {
            $conLog = new PDO($dsn, $username, $password, $options);
            $sqlLog = "INSERT INTO Log (userId, activity) VALUES (:userid, :activity)";
            $sthLog = $conLog->prepare($sqlLog);
            $sthLog->bindValue(':userid', $_SESSION['userid']);
            $sthLog->bindValue(':activity', "deleted payment method with Account #".$_GET['acc']);
            $sthLog->execute();
        } catch (PDOException $error) {
            echo $sqlLog . "<br>" . $error->getMessage();
        }
    }
    gotoPage("'applicantPaymentInfo.php'");
}
?>

<?php require "../templates/applicantHeader.php"; ?>

<h2>Payment Methods</h2>
<a href="applicantAddNewPay.php">Add New Payment Method</a>
<table>
    <thead>
    <tr>
        <th>Account Number</th>
        <th>Account Type</th>
        <th>Expiration Date</th>
        <th>CVN</th>
        <th>Default or Not</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($result): ?>
        <?php foreach ($result as $row) : ?>
            <tr>
                <td><?php echo escape($row["accountNumber"]); ?></td>
                <td><?php echo escape($row["payType"]); ?></td>
                <td><?php echo escape($row["expDate"]); ?></td>
                <td><?php echo escape($row["CVN"]); ?></td>
                <td><?php echo ($row["isDefault"] == '1') ? 'Yes' : 'No'; ?></td>
                <td>
                    <?php if ($row['isDefault'] = '0' or ($row['isDefault'] = '1' and count($result)>1)): ?>
                        <form name="actions" method="post"
                              action="applicantPaymentInfo.php?acc=<?php echo $row["accountNumber"]; ?>&payType=<?php echo $row["payType"] ?>">
                            <select name="selectPMAction" id="selectPMAction">
                                <option value="" disabled selected>Select</option>
                                <option value="makeDefault">Make Default</option>
                                <option value="delete">Delete</option>
                            </select>
                            <button type="submit">Submit</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>

<?php require "../templates/footer.php"; ?>

