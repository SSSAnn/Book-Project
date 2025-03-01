<?php
include 'config.php';
session_start();
$user_id  = $_SESSION["user_id"];
if (!isset($user_id)) {
    header("location:./login.php");
}

if (isset($_POST['add_to_cart'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];
    $check_cart_numbers = "SELECT *  FROM `cart` WHERE name=? AND user_id=?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $check_cart_numbers)) {
        echo "error in select";
    } else {
        mysqli_stmt_bind_param($stmt, 'si', $product_name, $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $message[] = "already add to cart!";
        } else {
            $insertCart = "INSERT INTO `cart`(user_id, name, price,quantity,image) VALUES(?,?,?,?,?)";

            if (!mysqli_stmt_prepare($stmt, $insertCart)) {
                echo "error in insert";
            } else {
                mysqli_stmt_bind_param($stmt, 'issis', $user_id, $product_name, $product_price, $product_quantity, $product_image);
                mysqli_stmt_execute($stmt);
                $message[] = "product add to cart!";
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
    <title>search page</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="./css/styles.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="heading">
        <h3>search page </h3>
        <p><a href="index.php">home</a> / search</p>
    </div>
    <section class="search-form">
        <form action="" method="post">
            <input type="text" name="search" placeholder="search products..." class="box">
            <input type="submit" name="submit" value="search" class="btn">
        </form>
    </section>
    <section class="products" style="padding-top:0">
        <div class="box-container">
            <?php
            if (isset($_POST["submit"])) {
                $search_item = $_POST["search"];
                $find_product = "SELECT * FROM `products` WHERE name LIKE ?";
                $search_item_like = "%$search_item%";
                $stmt = mysqli_stmt_init($conn);
              
                if (!mysqli_stmt_prepare($stmt, $find_product)) {
                    echo "error in select";
                } else {
                    mysqli_stmt_bind_param($stmt, "s", $search_item_like);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);
                    mysqli_stmt_bind_result($stmt, $id, $name, $price, $image);
                    if (mysqli_stmt_num_rows($stmt) > 0) {
                     
                        while (mysqli_stmt_fetch($stmt)) {
                           
            ?>
                            <form action="" method="post" class="box">
                                <img src="uploaded_img/<?php echo $image; ?>" alt="">
                                <div class="name"><?php echo $name; ?></div>
                                <div class="price">$<?php echo $price; ?>/-</div>
                                <input type="number" min="1" name="product_quantity" value="1" class="qty">
                                <input type="hidden" name="product_name" value="<?php echo $name; ?>">
                                <input type="hidden" name="product_price" value="<?php echo $price; ?>">
                                <input type="hidden" name="product_image" value="<?php echo $image; ?>">
                                <input type="submit" value="add to cart" name="add_to_cart" class="btn">
                            </form>



            <?php
                        }
                    } else {
                        echo '<p class="empty">no result found!</p>';
                    }
                }
            } else {
                echo '<p class="empty">search something</p>';
            }
            ?>

        </div>

    </section>


    <?php include 'footer.php'; ?>

    <script src="js/script.js"></script>
</body>

</html>