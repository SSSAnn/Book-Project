<?php
include 'config.php';
session_start();
$admin_id  = $_SESSION["admin_id"];
if (!isset($admin_id)) {
    header("location:./index.php");
}
if (isset($_POST["add_product"])) {
    $name = trim($_POST["name"]);
    $price = trim($_POST["price"]);

    $image = $_FILES["image"]["name"];
    $image_size = $_FILES["image"]["size"];
    $image_tmp_name = $_FILES["image"]["tmp_name"];
    $image_folder = 'uploaded_img/' . $image;
    $imageExtension = explode(".", $image);
    $allowedExtension = ["jpeg", "jpg"];



    $select_product_name = "SELECT name FROM `products` WHERE name = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $select_product_name)) {
    } else {
        mysqli_stmt_bind_param($stmt, "s", $name);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $message[] = "product name already added";
        } else {
            $add_product_query = "INSERT INTO `products`(name,price,image)VALUES(?,?,?)";

            if (!mysqli_stmt_prepare($stmt, $add_product_query)) {
                echo "error in insert";
            } else {
                if ($_FILES["image"]["error"] == UPLOAD_ERR_OK) {
                    if (in_array($imageExtension[1], $allowedExtension)) {
                        if ($image_size > 5000000) {
                            $message[] = "image size is too large and upload image size less than 5 MB";
                        } else {

                            mysqli_stmt_bind_param($stmt, 'sis', $name, $price, $image);
                            mysqli_stmt_execute($stmt);
                            move_uploaded_file($image_tmp_name, $image_folder);
                            $message[] = "Product added successful";
                        }
                    } else {
                        $message[] = "only jpeg and jgp";
                    }
                } else {
                    echo "upload error";
                }
            }
        }
    }
}

if (isset($_GET["delete"])) {
    $delete_id = $_GET["delete"];
    $delete_img_query = "SELECT image FROM `products` WHERE id=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $delete_img_query)) {
    } else {
        mysqli_stmt_bind_param($stmt, "i", $delete_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $image);
        if (mysqli_stmt_fetch($stmt)) {
            unlink('uploaded_img/' . $image);
        }
    }


    $deleteProduct = "DELETE FROM `products` WHERE id=?";

    if (!mysqli_stmt_prepare($stmt, $deleteProduct)) {
    } else {
        mysqli_stmt_bind_param($stmt, "i", $delete_id);
        mysqli_stmt_execute($stmt);
        header("location:admin_products.php");
    }
}

if (isset($_POST["update_product"])) {
    $update_p_id = $_POST["update_p_id"];
    $update_name = $_POST["update_name"];
    $update_price = $_POST["update_price"];
    $update_image = $_FILES["update_image"]['name'];
    $update_image_tmp_name = $_FILES["update_image"]['tmp_name'];
    $update_image_size = $_FILES["update_image"]['size'];
    $update_folder = 'uploaded_img/' . $update_image;
    $update_old_image = $_POST['update_old_image'];

    $updateProduct = "UPDATE `products` SET name = ?, price= ? WHERE id=?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $updateProduct)) {
    } else {
        mysqli_stmt_bind_param($stmt, "sii", $update_name, $update_price, $update_p_id);
        mysqli_stmt_execute($stmt);
        if (!empty($update_image)) {

            if ($update_image_size > 200000) {
                $message[] = "image file size is too large";
            } else {

                $updateProductImage = "UPDATE `products` SET image = ? WHERE id=?";


                if (!mysqli_stmt_prepare($stmt, $updateProductImage)) {
                    echo "update in w";
                } else {
                    mysqli_stmt_bind_param($stmt, "si", $update_image, $update_p_id);
                    mysqli_stmt_execute($stmt);
                    move_uploaded_file($update_image_tmp_name, $update_folder);
                    unlink('uploaded_img/' . $update_old_image);
                }
            }
        }
        header('location:admin_products.php');
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="./css/admin_style.css">
</head>

<body>
    <?php include_once 'admin_header.php' ?>
    <section class="add-products">
        <h1 class="title">shop products</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <h3>add product</h3>
            <input type="text" name="name" class="box" placeholder="enter product name" required id="">
            <input type="number" min="0" name="price" class="box" placeholder="enter product price" required>
            <input type="file" accept="image/jpg, image/jpeg, image/png" class="box" name="image" id="" required>
            <input type="submit" value="add product" name="add_product" class="btn">
        </form>

    </section>
    <section class="show-products">
        <div class="box-container">
            <?php
            $select_product = "SELECT * FROM `products`";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $select_product)) {
                echo "select error";
            } else {
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                mysqli_stmt_bind_result($stmt, $id, $name, $price, $image);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    while (mysqli_stmt_fetch($stmt)) {


            ?>
                        <div class="box">
                            <img src="uploaded_img/<?php echo $image ?>" alt="">
                            <div class="name"><?php echo $name ?></div>
                            <div class="name">$<?php echo $price ?>/-</div>
                            <a href="admin_products.php?update=<?php echo $id ?>" class="option-btn">update</a>
                            <a href="admin_products.php?delete=<?php echo $id ?>" class="delete-btn" onclick="confirm('delete this products?')">delete product</a>
                        </div>

            <?php
                    }
                } else {
                    echo ' <p class="empty">no product added yet</p>';
                }
            }
            ?>

        </div>
    </section>

    <section class="edit-product-form">
        <?php
        if (isset($_GET["update"])) {
            $update_id = $_GET["update"];
            $update_query = "SELECT * FROM `products` WHERE id= ?";
            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $update_query)) {
                echo "select error";
            } else {
                mysqli_stmt_bind_param($stmt, "i", $update_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    mysqli_stmt_bind_result($stmt, $id, $name, $price, $image);
                    while (mysqli_stmt_fetch($stmt)) {

        ?>
                        <form method="post" enctype="multipart/form-data">
                            <input type="hidden" name="update_p_id" value="<?php echo $id; ?>">
                            <input type="hidden" name="update_old_image" value="<?php echo $image; ?>">
                            <img src="uploaded_img/<?php echo $image; ?>" alt="">
                            <input type="text" name="update_name" value="<?php echo $name ?>" class="box" placeholder="enter product name" required>
                            <input type="number" name="update_price" value="<?php echo $price ?>" min="0" class="box" placeholder="enter product name" required>
                            <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
                            <input type="submit" value="update" name="update_product" class="btn">
                            <input type="reset" value="cancel" id="close_update" class="option-btn">
                        </form>
        <?php
                    }
                }
            }
        } else {
            echo '<script>
            document.querySelector(".edit-product-form").style.display ="none";
            </script>';
        }
        ?>
    </section>


    <script src="js/admin_script.js"></script>
</body>

</html>