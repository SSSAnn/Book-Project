<?php
include 'config.php';
session_start();
if(isset($_POST['submit'])){
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];
    $user_type = $_POST["user_type"];

   



    // $select_users = "SELECT * FROM `users` WHERE name = ? AND password = ?";
    $select_users = "SELECT * FROM `users` WHERE name = ? OR email = ?";
    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt ,$select_users)){
        die("select failed");
    }else{

        mysqli_stmt_bind_param($stmt, 'ss', $name, $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $row = mysqli_stmt_num_rows($stmt);
       ;
        if(mysqli_stmt_num_rows($stmt)>0){
            $message[] = "Name or email already exists";
        }else{
            $insert_users = "INSERT INTO `users`(name,email, password, user_type)VALUES(?,?,?,?)";
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $insert_users)){
              die("failed insert");
            }else{

                if($password !=$cpassword){
                    $message[]  = "Confirm password not matched";
                  
                }else{
                  $password = password_hash($password, PASSWORD_DEFAULT);
                    mysqli_stmt_bind_param($stmt, 'ssss', $name, $email, $password, $user_type);
                    mysqli_stmt_execute($stmt);
                    $_SESSION["message"] = "Register succesfully";
                    header("Location:./login.php");
                                    
                }
            }
        }
    }
    mysqli_stmt_free_result($stmt);
    mysqli_close($conn);



}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="./css/styles.css">

</head>
<body>
  
<?php
if(isset($message)){
    foreach($message as $message){
        echo 
        ' <div class="message">
        <span>'.$message.'</span>
        <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>';
    }
}
?>
<div class="form-container">
    <form action="" method="post">
        <h3>Register now</h3>
    <input type="text" name="name" placeholder="enter your name" required class="box">
    <input type="email" name="email" placeholder="enter your email" required class="box">
    <input type="password" name="password" placeholder="enter your password" required class="box">
    <input type="password" name="cpassword" placeholder="enter your confirm" required class="box">
    <select name="user_type" class="box">
    <option value="user">User</option>
        <option value="admin">Admin</option>
    </select>
    <input type="submit" name="submit" value="register now" class="btn"> 
    <p>already have an account? <a href="login.php">login now</a></p>
    </form>
</div>
    
</body>
</html>