<?php
/**
 * CST-126 Blog project
 * register.php version 1.0
 * Program Author: Evan Wilson, Branden Manibusan, and Nicholas Thomas
 * Date: 10/14/2018
 * Greeting/home page once logged in. Logic to prevent access without logging in, and to
 * greet the user by username
 * References: https://codewithawa.com/posts/complete-user-registration-system-using-php-and-mysql-database
 * Site was used in initial development of application with many changes implemented.
 */

//initializes session data
session_start();

//prevents anyone accessing this page without logging in first
if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: login.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Home Page</title>
    <!-- uses current system time in style.css call to ensure current updates without browser cache-->
    <link rel="stylesheet" type="text/css" href="css/style.css?<?php echo time(); ?>">
</head>
<body>
<div class="header">
    <h2>Home Page</h2>
</div>
<div class="content">
    <!-- notification message -->
    <?php if (isset($_SESSION['success'])) : ?>
        <div class="error success" >
            <h3>
                <?php
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
            </h3>
        </div>
    <?php endif ?>

    <!-- logged in user information -->
    <?php  if (isset($_SESSION['username'])) : ?>
        <p class="center">Welcome <strong><?php echo strtoupper($_SESSION['fname']); ?></strong> <br>Please select one of the options below.</p>
        <!-- future options here for additional functions such as change account details and such.  -->
        <!-- future - if username === admin then add administrative buttons to delete accounts and such -->
        <form method="post" action="home_forum.php?loggedin='1'">
            <div class="input-group">
                <button type="submit" class="btn" name="main_forum">FORUM</button>

            </div>
        </form>
    <?php endif ?>
</div>
</body>
<div class="footer">
    <p>
        <a href="index.php?logout='1'" style="color: white; float:right;">LOGOUT</a>
    </p>
    <br>
</div>


</html>


