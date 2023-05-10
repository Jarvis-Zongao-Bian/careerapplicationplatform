<?php
  session_start();
  include "database.php";

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    //something was posted
    $user_name = $_POST['user_name'];
    $email_address = $_POST['email_address'];

    $result = mysqli_query($con, "SELECT * FROM User where userID = '$user_name' and email = '$email_address'");
    $row = mysqli_fetch_array($result);
    if($row['userID'] ==$user_name && $row['email'] == $email_address )
    {
        $password = $row['password'];
        echo "Your password is: ".$password;
        echo "<br>";
        echo "Click Back to home to go back to Login page";

}else
{
    echo "wrong username or email!";
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
        <h2>Forget Password?</h1>  
        <form name="f1" onsubmit = "return validation()" method = "POST" >  
            <p>  
                <label> Username: </label>  
                <input type = "text" id ="user" name  = "user_name" />  
            </p>  
            <p>  
                <label> Email address: </label>  
                <input type = "text" id ="Email" name  = "email_address" />  
            </p>  
            <!-- <p>     
                <input type =  "submit" id = "btn" value = "submit" />  
            </p>   -->
            <div class="login__form_action_container ">
                <button class="btn__primary--large from__button--floating" data-litms-control-urn="login-submit" type="submit"  id = "btn" aria-label="Sign in">Submit</button>
            </div>
        </form>  
    </div>  
    <div class="join-now">
    <a href="./index.php" class="btn__tertiary--medium" id="join_now" data-litms-control-urn="login_join_now" data-cie-control-urn="join-now-btn">Back to Homepage</a>
    </div>  
</body>
</html>