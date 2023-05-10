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
    try  {
      $userid = $_SESSION['userid'];
      $role = $_SESSION['role'];

      $connection = new PDO($dsn, $username, $password, $options);
  
      $sql = "SELECT * 
              FROM Job
              WHERE category LIKE :category";
  
      $category = $_POST['category'];
      $statement = $connection->prepare($sql);
      $statement->bindValue(':category', "%".$category."%", PDO::PARAM_STR);
      $statement->execute();
  
      $result = $statement->fetchAll();
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }

    try {
        $conLog = new PDO($dsn, $username, $password, $options);
        $sqlLog = "INSERT INTO Log (userId, activity) VALUES (:userid, :activity)";
        $sthLog = $conLog->prepare($sqlLog);
        $sthLog->bindValue(':userid', $_SESSION['userid']);
        $sthLog->bindValue(':activity', "search for a job by category");
        $sthLog->execute();
    } catch (PDOException $error) {
        echo $sqlLog . "<br>" . $error->getMessage();
    }
}
?>

<?php include "../templates/applicantHeader.php"; ?>
<div class="row">
  <div class="side">
  <div class = "up"><h2>Search Jobs by Category</h2></div>

<form method="post">
  <!-- <input name="csrf" type="hidden" value="<?php echo escape($_SESSION['csrf']); ?>"> -->
  <label for="category">Category Name</label>
  <input type="text" id="category" name="category">
  <input type="submit" name="submit" value="View Results">
</form>
</div>
<div class="main">
<?php  
if (isset($_POST['submit'])) {
  if ($result && $statement->rowCount() > 0) { ?>
    <div class = "up"><h2>Results</h2></div>

    <table>
    <thead>
    <tr>
        <th>#</th>
        <th>Job Title</th>
        <th>Job Description</th>
        <th>Number of Opening</th>
        <th>Post Date</th>
        <th>Category</th>
        <th>Status</th>
        <th>Apply</th>
    </tr>
    </thead>
    <tbody>
        <?php if($result): ?>
            <?php foreach ($result as $row) : ?>
                <tr>
                    <td><?php echo escape($row["jobId"]); ?></td>
                    <td><?php echo escape($row["category"]); ?></td>
                    <td><?php echo escape($row["description"]); ?></td>
                    <td><?php echo escape($row["numberOfOpening"]); ?></td>
                    <td><?php echo escape($row["postDate"]); ?></td>
                    <td><?php echo escape($row["category"]); ?></td>
                    <td><?php echo escape($row["status"]); ?> </td>
                    <td><a href="applicantApply.php?id=<?php echo escape($row["jobId"]); ?>">Apply for Job</a>
                </tr>
            <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
    </table>
    <?php } else { ?>
      <blockquote>No results found for <?php echo escape($_POST['category']); ?>.</blockquote>
    <?php } 
} ?> 
  </div>
</div>

<?php include "../templates/footer.php"; ?>