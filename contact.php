<?php
include 'config.php';
session_start();
$user_id  = $_SESSION["user_id"];

if(!isset($user_id)){
    header("location:./login.php");
}

if(isset($_POST['send'])){
    $name = $_POST["name"];
    $email = $_POST["email"];
    $number = $_POST["number"];
    $msg = $_POST["message"];
    $select_message = "SELECT * FROM `message` WHERE  name =? AND email=? AND number=? AND message=? ";
    $stmt =mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt,$select_message)){
        echo "error in sle";
    }else{
        mysqli_stmt_bind_param($stmt,"ssis", $name, $email,$number,$msg);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if(mysqli_stmt_num_rows($stmt)>0){
            $message[] = "message sent already";
        }else{
            $insert_message = "INSERT INTO `message`(user_id, name, email, number,message) VALUES(?,?,?,?,?)";

            if(!mysqli_stmt_prepare($stmt, $insert_message)){
                echo "error";
            }else{
                mysqli_stmt_bind_param($stmt,'issis',$user_id,$name,$email,$number,$msg);
                mysqli_stmt_execute($stmt);
                $message[] = "message sent successfully";
            }
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
    <title>contact</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>
    <?php include 'header.php';?>

    <div class="heading">
        <h3>contact us</h3>
        <p><a href="index.php">home</a> / contact</p>
    </div>
    <section class="contact">
      
        <form action="" method="post">
        <h3>say something</h3>
        <input type="text" name="name" require placeholder="enter your name" class="box">
        <input type="email" name="email" require placeholder="enter your email" class="box">
        <input type="number" name="number" require placeholder="enter your number" class="box">
        <textarea name="message" class="box" placeholder="enter your message" id="" cols="30" rows="10"></textarea>
        <input type="submit" value="send message" name="send" class="btn">
        </form>
    </section>
    <?php include 'footer.php';?>

    <script src="js/script.js"></script>
</body>
</html>

