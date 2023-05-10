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

try {
    $connection = new PDO($dsn, $username, $password, $options);
    $sql = "SELECT * FROM Log;";
    $sth = $connection->query($sql, PDO::FETCH_ASSOC);
    $result = $sth->fetchAll();
} catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}
?>

<?php require "../templates/adminHeader.php"; ?>

<h2>System Activity</h2>

<table>
    <thead>
    <tr>
        <?php if($result[0]): ?>
            <?php foreach ($result[0] as $key => $value): ?>
                <th><?php echo ucwords(implode(' ',preg_split('/(?=[A-Z])/', $key))); ?></th>
            <?php endforeach; ?>
        <?php endif; ?>
    </tr>
    </thead>
    <tbody>
    <?php if($result): ?>
        <?php foreach ($result as $row) : ?>
            <tr>
                <?php if($row): ?>
                    <?php foreach ($row as $key => $value): ?>
                        <td><?php echo $value; ?></td>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>


<?php require "../templates/footer.php"; ?>
