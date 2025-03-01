<?php
include 'config.php';
session_start();
$admin_id  = $_SESSION["admin_id"];
if (!isset($admin_id)) {
    header("location:./index.php");
}

if (isset($_POST["update_order"])) {
    $order_update_id = $_POST['order_id'];
    $update_payment = $_POST['update_payment'];
     
      $order_update = "UPDATE  `orders`SET payment_status= ? WHERE id = ?";
      $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt,$order_update)){
            echo "error";
        }else{  
            mysqli_stmt_bind_param($stmt, "si",$update_payment,$order_update_id);
            mysqli_stmt_execute($stmt);
            $message[] = "payment status has beed updated";
        }

}
if(isset($_GET["delete"])){
    $delete_id = $_GET["delete"];
    $deleteOrder = "DELETE FROM `orders` WHERE id=?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $deleteOrder)){

    }else{
        mysqli_stmt_bind_param($stmt, "i", $delete_id);
        mysqli_stmt_execute($stmt);
        header("location:admin_orders.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="./css/admin_style.css">
</head>

<body>
    <?php include_once 'admin_header.php' ?>

    <section class="orders">
        <h1 class="title">placed orders</h1>
        <div class="box-container">
            <?php
            $select_orders = "SELECT *FROM `orders`";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $select_orders)) {
            } else {
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                mysqli_stmt_bind_result($stmt, $id, $user_id, $name, $number, $email, $method, $address, $total_products, $total_price, $placed_on, $payment_status);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    while (mysqli_stmt_fetch($stmt)) {


            ?>
                        <div class="box">
                            <p>user id : <span><?php echo $user_id ?></span></p>
                            <p>placed on : <span><?php echo $placed_on ?></span></p>
                            <p>name : <span><?php echo $name ?></span></p>
                            <p>number : <span><?php echo $number ?></span></p>
                            <p>email : <span><?php echo $email ?></span></p>
                            <p>address : <span><?php echo $address ?></span></p>
                            <p>total products : <span><?php echo $total_products ?></span></p>
                            <p>total price : <span><?php echo $total_price ?>/-</span></p>
                            <p>payment method : <span><?php echo $method ?></span></p>
                            <form action="" method="POST">
                                <input type="hidden" name="order_id" value="<?php echo $id; ?>">
                              
                                <select name="update_payment">
                                <option value="<?php echo $payment_status; ?>" selected readonly><?php echo $payment_status; ?></option>
                                    <option value="pending">pending</option>
                                    <option value="completed">completed</option>
                                </select>
                                <input type="submit" value="update" name="update_order" class="option-btn">
                                <a href="admin_orders.php?delete=<?php echo $id ?>" onclick="return confirm('delete this orders')" class="delete-btn">delete</a>
                            </form>
                        </div>
            <?php
                    }
                } else {
                    echo ' <p class="empty">no orders placed 
    yet!</p>';
                }
            }
            ?>
        </div>

    </section>



    <script src="js/admin_script.js"></script>
</body>

</html>