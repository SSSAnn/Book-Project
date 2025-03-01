<?php
include 'config.php';
session_start();
$admin_id  = $_SESSION["admin_id"];
if (!isset($admin_id)) {
    header("location:./index.php");
}

if(isset($_GET["delete"])){
    $delete_id = $_GET["delete"];
 
    $deleteMessage = "DELETE FROM `message` WHERE id=?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $deleteMessage)){
        echo "no";
    }else{
        mysqli_stmt_bind_param($stmt, "i", $delete_id);
        mysqli_stmt_execute($stmt);
        header("location:admin_contacts.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>message</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="./css/admin_style.css">
</head>

<body>
    <?php include_once 'admin_header.php' ?>

    <section class="messages">
     <h1 class="title">messages </h1>
     <div class="box-container">
     <?php
            $select_message = "SELECT * FROM `message`";
            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $select_message)) {
                echo "w";
            } else {
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                mysqli_stmt_bind_result($stmt,$id, $user_id, $name, $email,$number,$message);

                if (mysqli_stmt_num_rows($stmt)>0) {
                    while(mysqli_stmt_Fetch($stmt)){

            ?>
                <div class="box">
                    <p>name : <span><?php echo $name;?></span></p>
                    <p>number : <span><?php echo $number;?></span></p>
                    <p>email : <span><?php echo $email;?></span></p>
                    <p>message : <span><?php echo $message;?></span></p>
                    <a href="admin_contacts.php?delete=<?php echo $id ?>" onclick="return confirm('delete this message')" class="delete-btn">delete message</a>
                   
                    
                </div>
            <?php
  }
                }else{
                    echo ' <p class="empty">you have no message</p>';
                }
            };
            ?>
     </div>
    </section>

    <script src="js/admin_script.js"></script>
</body>

</html>