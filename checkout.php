<?php
include 'config.php';
session_start();
$user_id  = $_SESSION["user_id"];
if (!isset($user_id)) {
    header("location:./index.php");
}

if(isset($_POST["order_btn"])){
    $name = $_POST["name"];
    $number = $_POST["number"];
    $email = $_POST["email"];
    $method = $_POST["method"];
    $address = 'flat no.'.$_POST["flat"].','.$_POST["street"].','.$_POST["city"].','.$_POST["country"].'-'.$_POST["pin_code"];
    $placed_on = date('d-M-Y');
    $cart_total = 0;
    $cart_products[] = '';

    $cart_query = "SELECT * FROM `cart` WHERE user_id = ?";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$cart_query)){
        echo "select error in cart";
    }else{
        mysqli_stmt_bind_param($stmt,"i",$user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt,$id, $user_id, $name, $price, $quantity, $image);
        if(mysqli_stmt_num_rows($stmt)>0){
            while(mysqli_stmt_fetch($stmt)){
                $cart_products[] = $name.' ('.$quantity.')';
                $sub_total = ($price * $quantity);
                $cart_total += $sub_total;
              
            }
        }
    }
    $total_products = implode(",", $cart_products);
    $order_query = "SELECT * FROM `orders` WHERE name = ? AND number = ? AND email = ? AND method = ? AND address = ? AND total_products = ? AND total_price = ?";

    if(!mysqli_stmt_prepare($stmt,$order_query)){
        echo "select error in order";
    }else{
        mysqli_stmt_bind_param($stmt,"sissssi",$name, $number,$email,$method, $address, $total_products,$cart_total);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if($cart_total ==0){
            $message[] = "your cart is empty";
        }else{
            if(mysqli_stmt_num_rows($stmt)>0){
                $message[] = 'order already placed!';
            }else{
                $insert_order_query = "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES(?,?,?,?,?,?,?,?,?)";

                if(!mysqli_stmt_prepare($stmt,$insert_order_query)){
                    echo "insert errr in orders";
                }else{
                    mysqli_stmt_bind_param($stmt,"isissssis", $user_id, $name, $number,$email,$method, $address,$total_products,$cart_total,$placed_on);
                    mysqli_stmt_execute($stmt);
                    $message[] = "order placed successfully!";

                    $delete_cart_query ="DELETE FROM `cart` WHERE user_id = ?" ;

                    if(!mysqli_stmt_prepare($stmt, $delete_cart_query)){
                        echo "delete error";
                    }else{
                        mysqli_stmt_bind_param($stmt, "i", $user_id);
                        mysqli_stmt_execute($stmt);
                       
                    }
                }
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
    <title>checkout</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="./css/styles.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="heading">
        <h3>checkout </h3>
        <p><a href="index.php">home</a> / checkout</p>
    </div>
    <section class="display-order">
        <?php
        $grand_total = 0;
        $select_cart = "SELECT * FROM `cart` WHERE user_id =?";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $select_cart)) {
        } else {
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt, $id, $user_id, $name, $price, $quantity, $image);
            if (mysqli_stmt_num_rows($stmt) > 0) {
                while (mysqli_stmt_fetch($stmt)) {
                    $total_price = $price * $quantity;
                    $grand_total += $total_price;

        ?>
        <p><?php echo $name;?><span><?php echo '($'.$price.'/-' .'x'.$quantity.')';?></span></p>
        <?php
                }
            } else {
                echo ' <p class="empty">your cart is empty</p>';
            }
        }
        ?>
        <div class="grand-total">grand total : <span>$<?php echo $grand_total;?>/-</span></div>
    </section>
    <section class="checkout">

        <form action="" method="post">
            <h3>place your order</h3>
            <div class="flex">
                <div class="inputBox">
                    <span>your name :</span>
                    <input type="text" name="name" id="" required placeholder="enter your name">
                </div>
                <div class="inputBox">
                    <span>your number :</span>
                    <input type="text" name="number" id="" required placeholder="enter your number">
                </div>

                <div class="inputBox">
                    <span>your email :</span>
                    <input type="text" name="email" id="" required placeholder="enter your email">
                </div>
                <div class="inputBox">
                    <span>payment method:</span>
                   <select name="method" id="">
                    <option value="cash on delivery">cash on delivery</option>
                    <option value="credit card">credit card</option>
                    <option value="paypal">paypal</option>
                   
                   <option value="paytm">paytm</option>
                   </select>
                </div>
                <div class="inputBox">
                    <span>address line 01 :</span>
                    <input type="number" min="0" name="flat" id="" required placeholder="e.g flat no.">
                </div>
                <div class="inputBox">
                    <span>address line 01 :</span>
                    <input type="text" name="street" id="" required placeholder="e.g street name">
                </div>

                <div class="inputBox">
                    <span>city :</span>
                    <input type="text" name="city" id="" required placeholder="e.g numbai.">
                </div>

                <div class="inputBox">
                    <span>state :</span>
                    <input type="text" name="state" id="" required placeholder="e.g maharashtra.">
                </div>
                <div class="inputBox">
                    <span>country :</span>
                    <input type="text" name="country" id="" required placeholder="e.g india.">
                </div>

                <div class="inputBox">
                    <span>pin code :</span>
                    <input type="number" min="0"name="pin_code" id="" required placeholder="e.g 123456.">
                </div>
            </div>
            <input type="submit" value="order now" class="btn" name="order_btn">
        </form>
    </section>
    <?php include 'footer.php'; ?>

    <script src="js/script.js"></script>
</body>

</html>