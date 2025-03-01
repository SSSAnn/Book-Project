<?php
include 'config.php';
session_start();
$admin_id  = $_SESSION["admin_id"];
if (!isset($admin_id)) {
    header("location:./index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="./css/admin_style.css">
</head>

<body>
    <?php include_once 'admin_header.php' ?>

    <section class="dashboard">
        <h1 class="title">dashboard</h1>
        <div class="box-container">
            <div class="box">
                <?php
                $total_pedings = 0;
                $select_pending = "SELECT total_price FROM `orders` WHERE payment_status = ? ";
                $pending = "pending";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $select_pending)) {
                 echo "select error";
                } else {
                    mysqli_stmt_bind_param($stmt, 's', $pending);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);
                    if (mysqli_stmt_num_rows($stmt) > 0) {
                        mysqli_stmt_bind_result($stmt, $totalPrice);

                        while (mysqli_stmt_fetch($stmt)) {
                            $total_price = $totalPrice;
                            $total_pedings += $total_price;
                        };
                    }
                };
                ?>
                <h3><?php echo $total_pedings; ?></h3>
                <p>total pendings</p>

            </div>

            <div class="box">
                <?php
                $total_completed = 0;
                $select_completed = "SELECT total_price FROM `orders` WHERE payment_status= ? ";
                $completed = "completed";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $select_completed)) {
                } else {
                    mysqli_stmt_bind_param($stmt, 's', $completed);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);
                    if (mysqli_stmt_num_rows($stmt) > 0) {
                        mysqli_stmt_bind_result($stmt, $totalPrice);

                        while (mysqli_stmt_fetch($stmt)) {
                            $total_price = $totalPrice;
                            $total_completed += $total_price;
                        };
                    }
                };
                ?>
                <h3><?php echo $total_completed; ?></h3>
                <p>completed payments</p>
            </div>

            <div class="box">
                <?php
                $select_orders = "SELECT * FROM `orders`";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $select_orders)) {
                } else {

                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);
                    $number_of_orders = mysqli_stmt_num_rows($stmt);
                }
                ?>
                <h3><?php echo $number_of_orders; ?></h3>
                <p>order placed</p>
            </div>

            <div class="box">
                <?php
                $select_products = "SELECT * FROM `products`";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $select_products)) {
                } else {

                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);
                    $number_of_products = mysqli_stmt_num_rows($stmt);
                }
                ?>
                <h3><?php echo $number_of_products; ?></h3>
                <p>products added</p>
            </div>

            <div class="box">
                <?php
                $select_users = "SELECT * FROM `users` WHERE user_type = ?";
                $userType = "user";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $select_users)) {
                } else {
                    mysqli_stmt_bind_param($stmt, "s", $userType);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);
                    $number_of_users = mysqli_stmt_num_rows($stmt);
                }
                ?>
                <h3><?php echo $number_of_users; ?></h3>
                <p>normal users</p>
            </div>

            <div class="box">
                <?php
                $select_admins = "SELECT * FROM `users` WHERE user_type = ?";
                $userType = "admin";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $select_admins)) {
                } else {
                    mysqli_stmt_bind_param($stmt, "s", $userType);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);
                    $number_of_admins = mysqli_stmt_num_rows($stmt);
                }
                ?>
                <h3><?php echo $number_of_admins; ?></h3>
                <p>admin users</p>
            </div>

            <div class="box">
                <?php
                $select_account = "SELECT * FROM `users`";

                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $select_account)) {
                } else {

                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);
                    $number_of_account = mysqli_stmt_num_rows($stmt);
                }
                ?>
                <h3><?php echo $number_of_account; ?></h3>
                <p>total users</p>
            </div>

            <div class="box">
                <?php
                $select_message = "SELECT * FROM `message`";

                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $select_message)) {
                } else {

                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);
                    $number_of_message = mysqli_stmt_num_rows($stmt);
                }
                ?>
                <h3><?php echo $number_of_message; ?></h3>
                <p>new messages</p>
            </div>


        </div>
    </section>

    <script src="js/admin_script.js"></script>
</body>

</html>