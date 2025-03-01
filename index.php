<?php
include 'config.php';

session_start();
if (isset($_POST['add_to_cart']) && isset($_POST["user_id"])) {
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
} else if (isset($_POST['add_to_cart']) && !isset($_POST["user_id"])) {
    header('location:./login.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="./css/styles.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <section class="home">
        <div class="content">
            <h3>Hand Picked Book to your door.</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Similique ducimus, praesentium dolorum nostrum natus doloremque aperiam autem. Nostrum consequatur officiis, esse expedita vitae tempore repellat quae inventore qui perspiciatis voluptatum.</p>
            <a href="about.php" class="white-btn">discover more</a>
        </div>
    </section>
    <section class="products">
        <h1 class="title">latest products</h1>
        <div class="box-container">
            <?php
            $select_product = "SELECT * FROM `products` LIMIT 6";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $select_product)) {
            } else {
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
                    echo '<p class="empty">no products added yet</p>';
                }
            }
            ?>

        </div>
    </section>

    <div class="load-more" style="margin-top: 2rem;  text-align: center">
        <a href="shop.php" class="option-btn">load more</a>
    </div>

    <section class="about">
        <div class="flex">
            <div class="image">
                <img src="images/about-img.jpg" alt="">
            </div>
            <div class="content">
                <h3>about us</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio at laudantium modi ipsam deleniti! A sapiente dolore illo eveniet.</p>
                <a href="about.php" class="btn">read more</a>
            </div>
        </div>
    </section>

    <section class="home-contact">
        <div class="content">
            <h3>have any questions?</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nesciunt laborum totam officiis et iste voluptatum.</p>
            <a href="contact.php" class="white-btn">contact us</a>
        </div>
    </section>


    <?php include 'footer.php'; ?>

    <script src="js/script.js"></script>
</body>

</html>