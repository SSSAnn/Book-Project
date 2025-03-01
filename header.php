<?php


if (isset($message)) {
    foreach ($message as $message) {
        echo
        ' <div class="message">
        <span>' . $message . '</span>
        <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>';
    }
}
?>


<header class="header">
    <?php
    if (!isset($_SESSION["user_id"])) {
        echo '<div class="header-1">
<div class="flex">
    <div class="share">
        <a href="#" class="fab fa-facebook-f"></a>
        <a href="#" class="fab fa-twitter"></a>
        <a href="#" class="fab fa-instagram"></a>
        <a href="#" class="fab fa-linkedin"></a>
    </div>
    <p>new <a href="login.php">login</a> | <a href="register.php">register</a></p>
</div>
</div>';
    }
    ?>


    <div class="header-2">
        <div class="flex">
            <a href="index.php" class="logo">Bookly</a>
            <nav class="navbar">
                <a href="index.php">home</a>
                <a href="about.php">about</a>
                <a href="shop.php">shop</a>
                <a href="contact.php">contact</a>
                <a href="orders.php">orders</a>
            </nav>
            <div class="icons">
                <div id="menu-btn" class="fas fa-bars"></div>
                <a href="search_page.php" class="fas fa-search"></a>
                <div id="user-btn" class="fas fa-user"></div>
                <?php

                $select_cart_number = "SELECT * FROM `cart` WHERE user_id = ?";
                $stmt = mysqli_stmt_init($conn);

                if (!mysqli_stmt_prepare($stmt, $select_cart_number)) {
                } else {
                    if(isset($_SESSION["user_id"])){
                        $user_id = $_SESSION["user_id"];
                        mysqli_stmt_bind_param($stmt, 'i', $user_id);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_store_result($stmt);
                        $cart_rows_number = mysqli_stmt_num_rows($stmt);
                    }
                   
                }
                ?>
                <a href="cart.php"><i class="fas fa-shopping-cart"></i><span><?php if(isset($cart_rows_number)){
                echo $cart_rows_number; 
                }else{
                    echo  0;
                } ?></span></a>
            </div>

            <div class="user-box">
                <?php
                if (isset($_SESSION["user_id"])) {
                    echo "<p>username : <span> $_SESSION[user_name]</span></p>
                    <p>email : <span> $_SESSION[user_email]</span></p>
                    <a href='logout.php' class='delete-btn'>logout</a>";
                } else {
                    echo "<p>username : <span></span></p>
                    <p>email : <span></span></p>
                    <a href='login.php' class='delete-btn'>login</a>";
                }
                ?>





            </div>
        </div>
    </div>
</header>