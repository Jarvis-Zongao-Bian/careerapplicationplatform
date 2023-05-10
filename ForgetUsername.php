<?php
  session_start();
  include "database.php";

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    //something was posted
    $email_address = $_POST['email_address'];

    $result = mysqli_query($con, "SELECT * FROM User where email = '$email_address'");
    $row = mysqli_fetch_array($result);
    if($row['email'] == $email_address )
    {
        $username = $row['userID'];
        echo "Your Username is: ".$username;
        echo "<br>";
        echo "Click Back to home to go back to Login page";

}else
{
    echo "Email is not registered!";
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
        <h2>Forget Username?</h2>  
        <form name="f1" onsubmit = "return validation()" method = "POST" >    
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
