<?php
session_start();
require "database.php";
require "common.php";

if ($_SESSION['loggedin'] == TRUE) {
    if ($_SESSION['role'] = 'Employer') {
        gotoPage("'./employer/viewJob.php'");
    } elseif ($_SESSION['role'] = 'Applicant') {
        gotoPage("'./applicant/applicantJobList.php'");
    } elseif ($_SESSION['role'] = 'Admin') {
        gotoPage("'./admin/viewLog.php'");
    } else{
        gotoPage("'logout.php'");
    }
    exit();
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    //something was posted
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];
    $choice = $_POST['select'];

    $result = mysqli_query($con, "SELECT * FROM User where userId = '$user_name' and password = '$password'");
    $row = mysqli_fetch_array($result);

    if($row['userId'] ==$user_name && $row['password'] == $password )
    {
        echo $row['userId'];
        echo $user_name;

        if($choice== 'Applicant'){
            $result2 = mysqli_query($con, "SELECT * FROM Applicant where userId = '$user_name'");
            $row2 = mysqli_fetch_array($result2);
            echo "Applicant".$row['userId'];
            if($row2['userId'] ==$user_name){
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['userid'] = $user_name;
                $_SESSION['accountStatus'] = $row2['accountStatus'];
                $_SESSION['role'] = 'Applicant';
                gotoPage("'./applicant/applicantJobList.php'");
            }
            else{
                echo "this account does not have an applicant account";
            }
        }
        if($choice== 'Employer'){
            $result3 = mysqli_query($con, "SELECT * FROM Employer where userId = '$user_name'");
            $row3 = mysqli_fetch_array($result3);
            echo "Employer".$row['userId'];
            if($row3['userId'] ==$user_name){
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['userid'] = $user_name;
                $_SESSION['accountStatus'] = $row3['accountStatus'];
                $_SESSION['role'] = 'Employer';
                gotoPage("'./employer/viewJob.php'");
            }
            else{
                echo "this account does not have Employer account";
            }
        }
        if($choice== 'Admin'){
            $result4 = mysqli_query($con, "SELECT * FROM Administrator where userId = '$user_name'");
            $row4 = mysqli_fetch_array($result4);
            echo "Admin".$row['userId'];
            if($row['userId'] ==$user_name){
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['userid'] = $user_name;
                $_SESSION['role'] = 'Admin';
                gotoPage("'./admin/viewLog.php'");
            }
            else{
                echo "this account does not have Admin account";
            }
        }
    }else
    {
        echo "wrong username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CareerHub</title>
    <link rel="stylesheet" href = "css/index.css">
</head>
<body>
<div class = "card-layout">
    <div class = "headerContent">
        <h2 class = "headerContentHeading">CareerHub</h2>
        <p class = "headerContentSubheading">Stay updated on your professional world</p>
    </div>
    <form name="f1" onsubmit = "return validation()" method = "POST" > 
        <select name="select" >
            <option value="Employer">Employer</option>
            <option value="Applicant">Applicant</option>
            <option value="Admin">Admin</option>
        </select>
        <p>
            <label> Username: </label>
            <input type = "text" id ="user" name  = "user_name" />
        </p>
        <p>
            <label> Password: </label>
            <input type = "password" id ="pass" name  = "password" />
        </p>

        <a href="./ForgetUsername.php" class="btn__tertiary--medium forgot-password" data-cie-control-urn="forgot-password-btn">Forgot username?</a>

        <a href="./ForgetPassWord.php" class="btn__tertiary--medium forgot-password" data-cie-control-urn="forgot-password-btn">Forgot password?</a>
        

        <div class="login__form_action_container ">
            <button class="btn__primary--large from__button--floating" data-litms-control-urn="login-submit" type="submit"  id = "btn" aria-label="Sign in">Log In</button>
        </div>
    </form>
</div>

<div class="join-now">
    New to CareerHub? 
    <a href="./Register.php" class="btn__tertiary--medium" id="join_now" data-litms-control-urn="login_join_now" data-cie-control-urn="join-now-btn">Join now</a>
</div>

<div class = "function">

</div>
<script>
    function validation()
    {
        var id=document.f1.user.value;
        var ps=document.f1.pass.value;
        if(id.length=="" && ps.length=="") {
            alert("UserName and Password are empty");
            return false;
        }
        else
        {
            if(id.length=="") {
                alert("Username is empty");
                return false;
            }
            if (ps.length=="") {
                alert("Password field is empty");
                return false;
            }
        }
    }
</script>

</body>
</html>
