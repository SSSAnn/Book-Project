<?php
include 'config.php';
session_start();
if (isset($_POST['submit'])) {

    $email = $_POST["email"];
    $password = $_POST["password"];



    $select_users = "SELECT id, email, password,user_type FROM `users` WHERE email = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $select_users)) {
        die("select failed");
    } else {

        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            mysqli_stmt_bind_result($stmt, $id, $email, $pass, $usertype);
            while (mysqli_stmt_fetch($stmt)) {
                $passwordVerify = password_verify($password, $pass);
                echo $usertype;
                if ($usertype == "admin" && $passwordVerify) {
                    $_SESSION['admin_name'] = $usertype;
                    $_SESSION['admin_email'] = $email;
                    $_SESSION['admin_id'] = $id;
                    header("location:./admin_page.php");
                    exit();
                } else if ($usertype == "user" && $passwordVerify) {
                    $_SESSION['user_name'] = $usertype;
                    $_SESSION['user_email'] = $email;
                    $_SESSION['user_id'] = $id;
                    header("location:./index.php");
                    exit();
                } else {
                    $message[] = "Incorrect password ";
                }
            }
        } else {
            $message[] = "Incorrect email ";
        }
    }
    mysqli_stmt_free_result($stmt);
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="./css/styles.css">

</head>

<body>

    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo
            ' <div class="message">
        <span>' . $message . '</span>
        <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>';
        }
    } else if (isset($_SESSION["message"])) {
        echo
        ' <div class="message">
    <span>' . $_SESSION["message"] . '</span>
    <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
    </div>';
        unset($_SESSION["message"]);
    }
    ?>
    <div class="form-container">
        <form action="" method="post">
            <h3>Login now</h3>

            <input type="email" name="email" placeholder="enter your email" required class="box">
            <input type="password" name="password" placeholder="enter your password" required class="box">
            <input type="submit" name="submit" value="login now" class="btn">
            <p>don't have an account? <a href="register.php">register now</a></p>
        </form>
    </div>

</body>

</html>