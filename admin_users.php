<?php
include 'config.php';
session_start();
$admin_id  = $_SESSION["admin_id"];
if (!isset($admin_id)) {
    header("location:./index.php");
}

if(isset($_GET["delete"])){
    $delete_id = $_GET["delete"];
  
    $deleteUser = "DELETE FROM `users` WHERE id=?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $deleteUser)){
        echo "no";
    }else{
        mysqli_stmt_bind_param($stmt, "i", $delete_id);
        mysqli_stmt_execute($stmt);
        header("location:admin_users.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>users</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="./css/admin_style.css">
</head>

<body>
    <?php include_once 'admin_header.php' ?>

    <section class="users">
        <h1 class="title">user accounts </h1>
        <div class="box-container">
            <?php
            $select_users = "SELECT * FROM `users`";
            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $select_users)) {
            } else {
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt,$id, $name, $email, $password, $usertype);

                while (mysqli_stmt_fetch($stmt)) {


            ?>
                <div class="box">
                    <p>username : <span><?php echo $name;?></span></p>
                    <p>eamil : <span><?php echo $email;?></span></p>
                    <p>user type : <span style="color:<?php  if($usertype == 'admin'){
                        echo 'var(--orange)';
                    } ?>"><?php echo $usertype;?></span></p>
                    <a href="admin_users.php?delete=<?php echo $id ?>" onclick="return confirm('delete this user')" class="delete-btn">delete user</a>
                </div>
            <?php

                }
            };
            ?>
        </div>
    </section>

    <script src="js/admin_script.js"></script>
</body>

</html>