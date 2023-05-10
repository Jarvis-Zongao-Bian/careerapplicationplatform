<?php
session_start();
include "database.php";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $email_adress = $_POST['email_adress'];
    $Phone_number = $_POST['Phone_number'];
    $expiredate = date("Y-m-d");

    $result = mysqli_query($con, "SELECT * FROM User");
    $row = mysqli_fetch_array($result);

    if ($row['userId'] == $user_name) {
        echo "The username is already exisited";
    }
    if ($row['email'] == $email_adress) {
        echo "The email is already used";
    }

    if ($row['userId'] != $user_name && $row['email'] != $email_adress) {
        $sql = "INSERT INTO User (userID,password,name,email,phone) 
            VALUES('$user_name', '$password', '$name', '$email_adress', '$Phone_number')";
        if (mysqli_query($con, $sql)) {
            $sql2 = "INSERT INTO Employer (userId,category,payOption,accountStatus,balance,expDate ) 
                VALUES('$user_name', 'Basic', 'Manual', 'Frozen', '0', '$expiredate')";
            if (mysqli_query($con, $sql2)) {
                $sql3 = "INSERT INTO Applicant (userId,category,payOption,accountStatus,balance,expDate) 
                    VALUES('$user_name', 'Basic', 'Manual', 'Active', '0','$expiredate')";
                if (mysqli_query($con, $sql3)) {

                    echo "Records inserted successfully";
                    echo "Registration Success!";
                    echo "Please click on Back to home to Login";
                }
            }
        } else {
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href = "css/index.css">
</head>

<body>
<div class = "card-layout">
<div class = "headerContent">
<h2 class = "headerContentHeading">Register</h2>
</div>  
    <!-- <div class = "center"> -->
        <form name="f1" onsubmit = "return validation()" method = "POST" >  
            <p>  
                <label> Username: </label>  
                <input type = "text" id ="user" name  = "user_name" />  
            </p>  
            <p>  
                <label> Password: </label>  
                <input type = "password" id ="pass" name  = "password" />  
            </p> 
            <p>  
                <label> Name: </label>  <br>
                <input type = "name" id ="name" name  = "name" />  
            </p> 
            
            <p>  
                <label> Email Address: </label>  
                <input type = "email" id ="email" name  = "email_adress" />  
            </p> 
            <p>  
                <label> Phone number: </label>  
                <input type = "Phone" id ="Phone" name  = "Phone_number" />  
            </p> 
            <!-- <p>     
                <input type =  "submit" id = "btn" value = "Registration" />  
            </p>   -->
            <div class="login__form_action_container ">
                <button class="btn__primary--large from__button--floating" data-litms-control-urn="login-submit" type="submit"  id = "btn" aria-label="Sign in">Register</button>
            </div>
    <!-- </div> -->
            

       
        </form></div>
        <div class="join-now">
    <a href="./index.php" class="btn__tertiary--medium" id="join_now" data-litms-control-urn="login_join_now" data-cie-control-urn="join-now-btn">Back to Homepage</a>
    </div>  

<script>
    function validation() {
        var id = document.f1.user.value;
        var ps = document.f1.pass.value;
        if (id.length == "" && ps.length == "") {
            alert("User Name and Password fields are empty");
            return false;
        } else {
            if (id.length == "") {
                alert("User Name is empty");
                return false;
            }
            if (ps.length == "") {
                alert("Password field is empty");
                return false;
            }
        }
    }
</script>

</body>

</html>