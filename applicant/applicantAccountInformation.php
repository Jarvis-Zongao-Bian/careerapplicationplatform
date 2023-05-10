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
$userid = $_SESSION['userid'];
$role = $_SESSION['role'];

if (isset($_POST['submitCategory'])) {
    try {
        $connection = new PDO($dsn, $username, $password, $options);

        if ($role == 'Employer') {
            $sqlCategory = "UPDATE Employer SET category=:category WHERE userId=:userid";
        } else if ($role == 'Applicant') {
            $sqlCategory = "UPDATE Applicant SET category=:category WHERE userId=:userid";
        } else {
            echo "Role is incorrect. Please log out and log in again.";
            exit();
        }

        $sthCategory = $connection->prepare($sqlCategory);
        $sthCategory->bindValue(':category', $_POST['selectCategory']);
        $sthCategory->bindValue(':userid', $userid);
        $sthCategory->execute();
    } catch(PDOException $error) {
        echo $sqlCategory . "<br>" . $error->getMessage();
    }

    try {
        $conLog = new PDO($dsn, $username, $password, $options);
        $sqlLog = "INSERT INTO Log (userId, activity) VALUES (:userid, :activity)";
        $sthLog = $conLog->prepare($sqlLog);
        $sthLog->bindValue(':userid', $_SESSION['userid']);
        $sthLog->bindValue(':activity', "changed user category to ".$_POST['selectCategory']);
        $sthLog->execute();
    } catch (PDOException $error) {
        echo $sqlLog . "<br>" . $error->getMessage();
    }
}

if (isset($_POST['submitPayOption'])) {
    try {
        $connection = new PDO($dsn, $username, $password, $options);

        if ($role == 'Employer') {
            $sqlPayOption = "UPDATE Employer SET payOption=:payOption WHERE userId=:userid";
        } else if ($role == 'Applicant') {
            $sqlPayOption = "UPDATE Applicant SET payOption=:payOption WHERE userId=:userid";
        } else {
            echo "Role is incorrect. Please log out and log in again.";
            exit();
        }

        $sthPayOption = $connection->prepare($sqlPayOption);
        $sthPayOption->bindValue(':payOption', $_POST['selectPayOption']);
        $sthPayOption->bindValue(':userid', $userid);
        $sthPayOption->execute();
    } catch(PDOException $error) {
        echo $sqlPayOption . "<br>" . $error->getMessage();
    }

    try {
        $conLog = new PDO($dsn, $username, $password, $options);
        $sqlLog = "INSERT INTO Log (userId, activity) VALUES (:userid, :activity)";
        $sthLog = $conLog->prepare($sqlLog);
        $sthLog->bindValue(':userid', $_SESSION['userid']);
        $sthLog->bindValue(':activity', "changed payment option to ".$_POST['selectPayOption']);
        $sthLog->execute();
    } catch (PDOException $error) {
        echo $sqlLog . "<br>" . $error->getMessage();
    }
}

if (isset($_POST['submitReload'])) {
    try {
        $newBalance = intval(substr($_POST['balance'],1)) + intval($_POST['reload']);
        $currExp = $_POST['expDate'];
        $fees = array(
            "Basic" => 0,
            "Prime" => ($role=='Applicant') ? 10 : 50,
            "Gold" => ($role=='Applicant') ? 20 : 100,
        );
        $monthFee = $fees[$_SESSION['category']];
        $extension = ($monthFee > 0) ? floor(intval($_POST['reload']) / $monthFee): 0;

        if ($newBalance >= 0.0) {
            $_SESSION['accountStatus'] = 'Active';
        }
        $connection = new PDO($dsn, $username, $password, $options);

        if ($role == 'Employer') {
            $sqlReload = "UPDATE Employer SET balance=:balance, expDate=(SELECT DATE_ADD(:currExp, INTERVAL :ext MONTH )) WHERE userId=:userid;";
        } else if ($role == 'Applicant') {
            $sqlReload = "UPDATE Applicant SET balance=:balance, expDate=(SELECT DATE_ADD(:currExp, INTERVAL :ext MONTH )) WHERE userId=:userid;";
        } else {
            echo "Role is incorrect. Please log out and log in again.";
            exit();
        }

        $sthReload = $connection->prepare($sqlReload);
        $sthReload->bindValue(':balance', $newBalance);
        $sthReload->bindValue(':userid', $userid);
        $sthReload->bindValue(':currExp', $currExp);
        $sthReload->bindValue(':ext', $extension);
        $sthReload->execute();
    } catch(PDOException $error) {
        echo $sqlReload . "<br>" . $error->getMessage();
    }

    try {
        $conLog = new PDO($dsn, $username, $password, $options);
        $sqlLog = "INSERT INTO Log (userId, activity) VALUES (:userid, :activity)";
        $sthLog = $conLog->prepare($sqlLog);
        $sthLog->bindValue(':userid', $_SESSION['userid']);
        $sthLog->bindValue(':activity', "reloaded $".$_POST['reload']);
        $sthLog->execute();
    } catch (PDOException $error) {
        echo $sqlLog . "<br>" . $error->getMessage();
    }
}

try {
    $connection = new PDO($dsn, $username, $password, $options);

    if ($role == 'Employer') {
        $sql = "SELECT * FROM Employer WHERE userId=:userid";
    } else if ($role == 'Applicant') {
        $sql = "SELECT * FROM Applicant WHERE userId=:userid";
    } else if ($role == 'Administrator') {
        $sql = "SELECT * FROM Administrator WHERE userId=:userid";
    } else {
        echo "Role is incorrect. Please log out and log in again.";
        exit();
    }

    $statement = $connection->prepare($sql);
    $statement->bindValue(':userid', $userid);
    $statement->execute();

    $accInfo = $statement->fetch(PDO::FETCH_ASSOC);
    $_SESSION['category'] = $accInfo['category'];

} catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}
?>

<?php include "../templates/applicantHeader.php"; ?>
<?php if ((isset($_POST['submitCategory']) && $sthCategory) or (isset($_POST['submitPayOption']) && $sthPayOption) or (isset($_POST['submitReload']) && $sthReload)): ?>
    <?php echo "Your account information is successfully updated.";?>
<?php endif ?>
    <div>
        <h2>Account Information</h2></div>
    <form method="post">
        <label for="userId">User ID</label>
        <input type="text" name="userId" id="userId" value="<?php echo $accInfo['userId'];?>"  readonly>
    </form>
    <form method="post">
        <label for="selectCategory">Category</label><br>
        <select name="selectCategory" id="selectCategory">
            <option value="" disabled selected><?php echo $accInfo['category']; ?></option>
            <?php if($role=='Applicant'): ?>
                <option value='Basic'>Basic - Free</option>";
            <?php endif ?>
            <option value="Prime">Prime - $<?php echo ($role=='Applicant') ? 10 : 50; ?>/month</option>
            <option value="Gold">Gold - $<?php echo ($role=='Applicant') ? 20 : 100; ?>/month</option>
        </select>
        <input type="submit" name="submitCategory" value="Save your choice">
    </form>
    <form method="post">
        <label for="selectPayOption">Payment Option</label><br>
        <select name="selectPayOption" id="selectPayOption">
            <option value="" disabled selected><?php echo $accInfo['payOption']; ?></option>
            <option value="Manual">Manual</option>
            <option value="Automatic">Automatic</option>
        </select>
        <input type="submit" name="submitPayOption" value="Save your choice">
    </form>
    <form method="post">
        <label for="expDate">Account Expiration Date</label>
        <input type="text" name="expDate" id="expDate" value="<?php echo $accInfo['expDate'];?>"  readonly>
        <label for="balance">Balance</label>
        <input type="text" name="balance" id="balance" value="$<?php echo $accInfo['balance'];?>"  readonly>
        <?php if ($accInfo['payOption'] == 'Manual'): ?>
            <label for="reload">Reload Balance</label>
            <input type="text" name="reload" id="reload">
            <input type="submit" name="submitReload" value="Confirm Reload">
        <?php endif ?>
    </form>


<?php require "../templates/footer.php"; ?>