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

$userid = $_SESSION['userid'];
$role = $_SESSION['role'];

try {
    $connection = new PDO($dsn, $username, $password, $options);

    $sql = "SELECT * FROM User WHERE userID=:userid";

    $statement = $connection->prepare($sql);
    $statement->bindValue(':userid', $userid);
    $statement->execute();

    $userInfo = $statement->fetch(PDO::FETCH_ASSOC);

} catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}

if (isset($_POST['submitpassword'])) {
        try {
            $conUpdate = new PDO($dsn, $username, $password, $options);
            $sqlUpdate = "UPDATE User SET password=:password WHERE userId=:userid";
            $sthUpdate = $connection->prepare($sqlUpdate);
            $sthUpdate->bindValue(':userid', $userid);
            $sthUpdate->bindValue(':password', $_POST['password']);
            $sthUpdate->execute();
        } catch (PDOException $error) {
            echo $sqlUpdate . "<br>" . $error->getMessage();
        }

        try {
            $conLog = new PDO($dsn, $username, $password, $options);
            $sqlLog = "INSERT INTO Log (userId, activity) VALUES (:userid, :activity)";
            $sthLog = $conLog->prepare($sqlLog);
            $sthLog->bindValue(':userid', $_SESSION['userid']);
            $sthLog->bindValue(':activity', "updated password");
            $sthLog->execute();
        } catch (PDOException $error) {
            echo $sqlLog . "<br>" . $error->getMessage();
        }
    gotoPage("'personalInfo.php'");
} elseif (isset($_POST['submitemail'])) {
    try {
        $conUpdate = new PDO($dsn, $username, $password, $options);
        $sqlUpdate = "UPDATE User SET email=:email WHERE userId=:userid";
        $sthUpdate = $connection->prepare($sqlUpdate);
        $sthUpdate->bindValue(':userid', $userid);
        $sthUpdate->bindValue(':email', $_POST['email']);
        $sthUpdate->execute();
    } catch (PDOException $error) {
        echo $sqlUpdate . "<br>" . $error->getMessage();
    }

    try {
        $conLog = new PDO($dsn, $username, $password, $options);
        $sqlLog = "INSERT INTO Log (userId, activity) VALUES (:userid, :activity)";
        $sthLog = $conLog->prepare($sqlLog);
        $sthLog->bindValue(':userid', $_SESSION['userid']);
        $sthLog->bindValue(':activity', "updated email");
        $sthLog->execute();
    } catch (PDOException $error) {
        echo $sqlLog . "<br>" . $error->getMessage();
    }
    gotoPage("'personalInfo.php'");
} elseif (isset($_POST['submitphone'])) {
    try {
        $conUpdate = new PDO($dsn, $username, $password, $options);
        $sqlUpdate = "UPDATE User SET phone=:phone WHERE userId=:userid";
        $sthUpdate = $connection->prepare($sqlUpdate);
        $sthUpdate->bindValue(':userid', $userid);
        $sthUpdate->bindValue(':phone', $_POST['phone']);
        $sthUpdate->execute();
    } catch (PDOException $error) {
        echo $sqlUpdate . "<br>" . $error->getMessage();
    }

    try {
        $conLog = new PDO($dsn, $username, $password, $options);
        $sqlLog = "INSERT INTO Log (userId, activity) VALUES (:userid, :activity)";
        $sthLog = $conLog->prepare($sqlLog);
        $sthLog->bindValue(':userid', $_SESSION['userid']);
        $sthLog->bindValue(':activity', "updated phone");
        $sthLog->execute();
    } catch (PDOException $error) {
        echo $sqlLog . "<br>" . $error->getMessage();
    }
    gotoPage("'personalInfo.php'");
}
?>

<?php include "../templates/employerHeader.php";?>
<?php if (isset($_POST['submit']) && $statement): ?>
    <?php echo "Your personal information is successfully saved." ?>
<?php endif; ?>
<h2>Personal Information</h2>
    <form method="post">
        <?php if($userInfo): ?>
            <?php foreach ($userInfo as $key =>$value): ?>
                <label for="<?php echo $key; ?>"> <?php echo ucwords(implode(' ',preg_split('/(?=[A-Z])/', $key))); ?></label>
                <input type="text" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo $value; ?>" <?php echo ($key == 'userID' | $key =='name' ? 'readonly' : null); ?>>
                <?php if ($key != 'userId' && $key !='name'): ?>
                    <input type='submit' name="<?php echo 'submit'.$key ?>" value='Save Edit'> <br>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </form>

<?php include "../templates/footer.php"; ?>