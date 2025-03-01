<?php
include 'config.php';
session_start();
$user_id  = $_SESSION["user_id"];
if (!isset($user_id)) {
    header("location:./login.php");
}
if (isset($_POST['update_cart'])) {
    $cart_id =  $_POST["cart_id"];
    $cart_quantity =  $_POST["cart_quantity"];
    $update_cart_query = "UPDATE `cart` SET quantity = ? WHERE id = ? ";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $update_cart_query)) {
    } else {
        mysqli_stmt_bind_param($stmt, "is", $cart_quantity, $cart_id);
        mysqli_stmt_execute($stmt);
        $message[] = "cart quantity updated!";
    }
}
if (isset($_GET["delete"])) {
    $delete_id = $_GET["delete"];

    $delete_query = "DELETE FROM `cart` WHERE id= ? ";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $delete_query)) {
    } else {

        mysqli_stmt_bind_param($stmt, "i", $delete_id);
        mysqli_stmt_execute($stmt);
        header('location:cart.php');
    }
}

if (isset($_GET["delete_all"])) {

    $delete_query = "DELETE FROM `cart` WHERE user_id= ? ";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $delete_query)) {
        echo "delete";
    } else {
        echo "delete";
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        header('location:cart.php');
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cart</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="./css/styles.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="heading">
        <h3>shopping cart</h3>
        <p><a href="index.php">home</a> / cart</p>
    </div>
    <section class="shopping-cart">
        <h1 class="title">products added</h1>
        <div class="box-container">
            <?php
            $grand_total = 0;
            $select_cart = "SELECT * FROM `cart` WHERE user_id=?";
            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $select_cart)) {
                echo "error";
            } else {
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                mysqli_stmt_bind_result($stmt, $id, $user_id, $name, $price, $quantity, $image);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    while (mysqli_stmt_fetch($stmt)) {

            ?>
                        <div class="box">
                            <a href="cart.php?delete=<?php echo $id; ?>" class="fas fa-times" onclick="return confirm('delete this from cart?')"></a>
                            <img src="uploaded_img/<?php echo $image; ?>" alt="">
                            <div class="name"><?php echo $name; ?></div>
                            <div class="price"><?php echo $price; ?>/-</div>
                            <form action="" method="post">
                                <input type="hidden" name="cart_id" value="<?php echo $id; ?>">
                                <input type="number" min="1" name="cart_quantity" id="" value="<?php echo $quantity; ?>">
                                <input type="submit" name="update_cart" value="update" class="option-btn">
                            </form>
                            <div class="sub-total">sub total : <span>$<?php echo $sub_total = $quantity * $price; ?>/-</span></div>
                        </div>

            <?php
                        $grand_total += $sub_total;
                    }
                } else {
                    echo ' <p class="empty">your cart is empty</p>';
                }
            }
            ?>

        </div>
        <div style="margin-top:2rem; text-align:center">
            <a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>" onclick="return confirm('delete all from cart?')">delete all</a>
        </div>

        <div class="cart-total">
            <p>grand total : <span>$<?php echo $grand_total; ?></span></p>
            <div class="flex">
                <a href="shop.php" class="option-btn">continue shopping</a>
                <a href="checkout.php" class="btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">procedd to checkout</a>
            </div>
        </div>
    </section>
    <?php include 'footer.php'; ?>

    <script src="js/script.js"></script>
</body>

</html>