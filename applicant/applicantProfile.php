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

if (isset($_POST['submit'])) {
    try {
        $connection = new PDO($dsn, $username, $password, $options);
        $user =[
            "userId"        => $_POST['userId'],
            "password" => $_POST['password'],
            "name"  => $_POST['name'],
            "email"     => $_POST['email'],
            "phone"       => $_POST['phone']
        ];

        $sql = "UPDATE User
			SET userId = :userId,
			password = :password,
			name = :name,
			email = :email,
			phone = :phone
			WHERE userId = :userId";

        $statement = $connection->prepare($sql);
        $statement->execute($user);
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }

    try {
        $conLog = new PDO($dsn, $username, $password, $options);
        $sqlLog = "INSERT INTO Log (userId, activity) VALUES (:userid, :activity)";
        $sthLog = $conLog->prepare($sqlLog);
        $sthLog->bindValue(':userid', $_SESSION['userid']);
        $sthLog->bindValue(':activity', "updated personal information");
        $sthLog->execute();
    } catch (PDOException $error) {
        echo $sqlLog . "<br>" . $error->getMessage();
    }
}

try {
    $userid = $_SESSION['userid'];
    $role = $_SESSION['role'];

    $connection = new PDO($dsn, $username, $password, $options);

    $sql = "SELECT * FROM User WHERE userId=:userid";

    $statement = $connection->prepare($sql);
    $statement->bindValue(':userid', $userid);
    $statement->execute();

    $userInfo = $statement->fetch(PDO::FETCH_ASSOC);

} catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}
?>

<?php include "../templates/applicantHeader.php"; ?>
<?php if (isset($_POST['submit']) && $statement) : ?>
    <?php echo "Your personal information is successfully saved." ?>
<?php endif; ?>
<div class = "up">
<h2>Personal Information</h2></div>
    
    <form method="post">
        <?php if($userInfo): ?>
            <?php foreach ($userInfo as $key => $value): ?>
                <label for="<?php echo $key; ?>"><?php echo ucwords(implode(' ',preg_split('/(?=[A-Z])/', $key))); ?></label>
                <input type="text" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo $value; ?>" <?php echo ($key == 'userId' | $key =='name' ? 'readonly' : null); ?>>
                <?php if ($key != 'userId' && $key !='name')
                    echo "<input type='submit' name='submit' value='Save Edit'><br>"
                    ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </form>


<?php require "../templates/footer.php"; ?>