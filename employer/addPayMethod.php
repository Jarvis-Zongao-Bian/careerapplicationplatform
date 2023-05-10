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

if (isset($_POST['submitPM'])) {

    try {
        $connection = new PDO($dsn, $username, $password, $options);

        if ($_POST['payType'] == 'Credit') {
            $sql = "INSERT INTO PayMethod VALUES (:accountNumber, :payType, :expDate, :CVN);
                    INSERT INTO PayWith VALUES (:userid, :accountNumber, :payType, 0);";
        } else {
            $sql = "INSERT INTO PayMethod VALUES (:accountNumber, :payType, NULL, NULL);
                    INSERT INTO PayWith VALUES (:userid, :accountNumber, :payType, 0);";
        }

        $statement = $connection->prepare($sql);
        $statement->bindValue(':userid', $_SESSION['userid']);
        $statement->bindValue(':accountNumber', $_POST['accountNumber']);
        $statement->bindValue(':payType', $_POST['payType']);
        if ($_POST['payType'] == 'Credit') {
            $statement->bindValue(':expDate', $_POST['expDate']);
            $statement->bindValue(':CVN', $_POST['CVN']);
        }

        $statement->execute();
    } catch (PDOException $error) {
        echo $sql."<br>".$error->getMessage();
    }

    try {
        $conLog = new PDO($dsn, $username, $password, $options);
        $sqlLog = "INSERT INTO Log (userId, activity) VALUES (:userid, :activity)";
        $sthLog = $conLog->prepare($sqlLog);
        $sthLog->bindValue(':userid', $_SESSION['userid']);
        $sthLog->bindValue(':activity', "added a new payment method with Account #".$_POST['accountNumber']);
        $sthLog->execute();
    } catch (PDOException $error) {
        echo $sqlLog . "<br>" . $error->getMessage();
    }
}
?>

<?php include "../templates/employerHeader.php"; ?>
<?php if (isset($_POST['submitPM']) && $statement) : ?>
    <?php echo "New payment method with account #".escape($_POST['accountNumber']); ?> successfully posted.
<?php endif; ?>
<form method="post">
    <label for="accountNumber">Account Number <br> (Credit card number for credit or transit+institution+account for debit)</label>
    <input type="text" name="accountNumber" id="accountNumber">
    <label for="payType">Select your account type:</label><br>
    <select name="payType" id="payType">
        <option value="Credit" selected>Credit</option>
        <option value="Debit">Debit</option>
    </select><br>
    <label for="expDate">Expiration Date (MM/YY)</label>
    <input type="text" name="expDate" id="expDate">
    <label for="CVN">CVN</label>
    <input type="text" name="CVN" id="CVN">
    <input type="submit" name="submitPM" value="Submit">
</form>
<a href="payInfo.php">Back to payment information</a>
<?php include "../templates/footer.php"; ?>
