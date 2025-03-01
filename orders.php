<?php
include 'config.php';
session_start();
$user_id  = $_SESSION["user_id"];
if (!isset($user_id)) {
    header("location:./login.php");
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

    <link rel="stylesheet" href="./css/styles.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="heading">
        <h3>placed orders</h3>
        <p><a href="index.php">home</a> / orders</p>
    </div>
    <section class="placed-orders">
        <h1 class="title">placed orders</h1>
        <div class="box-container">
            <?php
            $order_query = "SELECT * FROM `orders` WHERE user_id = ?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $order_query)) {
            } else {
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                mysqli_stmt_bind_result($stmt, $id, $user_id, $name, $number, $email, $method, $address, $total_products, $total_price, $placed_on, $payment_status);

                if (mysqli_stmt_num_rows($stmt) > 0) {
                    while (mysqli_stmt_fetch($stmt)) {

            ?>
                        <div class="box">
                            <p> placed on : <span><?php echo $placed_on; ?></span></p>
                            <p> name : <span><?php echo $name; ?></span></p>
                            <p> number : <span><?php echo $number; ?></span></p>
                            <p> email : <span><?php echo $email; ?></span></p>
                            <p> address : <span><?php echo $address; ?></span></p>
                            <p> payment method : <span><?php echo $method; ?></span></p>
                            <p> your orders: <span><?php echo $total_products; ?></span></p>
                            <p> total price : <span>$<?php echo $total_price; ?>/-</span></p>
                            <p> payment status: <span style="color:<?php if ($payment_status == 'pending') {
                                                                        echo 'red';
                                                                    } else {
                                                                        echo 'green';
                                                                    } ?>"><?php echo $payment_status; ?></span></p>
                        </div>
            <?php
                    }
                } else {
                    echo ' <p class="empty">no orders placed yet!</p>';
                }
            }
            ?>
        </div>
    </section>
    <?php include 'footer.php'; ?>

    <script src="js/script.js"></script>
</body>

</html>