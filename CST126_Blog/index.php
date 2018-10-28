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
<!-- Backup header
<div class="header">
    <h2>Home Page</h2>
</div>
-->
<!-- Implement new header -->
<div class="header">
    <a href="#default" class="logo">CST126 Blog</a>
    <div class="header-right">
        <a class="active" href="home_forum.php?refresh='1'">Forum</a>
        <a href="index.php?logout='1'">Logout</a>
    </div>
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
        <p class="center"
           style="
                color: dodgerblue;
                padding: 5px 10px;
                font-size: 32px;
                font-weight: bold;
                border-radius: 5px;"
           s>Welcome <strong><?php echo strtoupper($_SESSION['fname']); ?></strong><br>Please select Forum or Logout on the top right. </p>
        <!-- future options here for additional functions such as change account details and such.  -->
        <!-- future - if username === admin then add administrative buttons to delete accounts and such -->
        <!-- TODO: Remove <form method="post" action="home_forum.php?loggedin='1'">-->
        <!-- TODO: Remove when we test
        <div class="flex-container">
            <div class="input-group">
                <button type="submit" class="btn" name="main_forum">FORUM</button>
            </div>
            </div>
            </form>
            -->
            <form method="post" action="admin_accounts.php">
            <?php 
            if ($_SESSION['username'] === "admin"):
            ?>
            <div class="flex-container">
            <div class="input-group">
                <button type="submit" class="btn" name="admin_accounts">ACCOUNTS</button>
            </div>
            <?php endif ?>
        </div>
        
        </form>
    <?php endif ?>
</div>
</body>

<!-- TODO: Delete when everyone test
<div class="footer">
    <p>
        <a href="index.php?logout='1'" style="color: white; float:right;">LOGOUT</a>
    </p>
    <br>
</div>
-->

</html>


