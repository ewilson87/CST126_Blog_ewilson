<?php
/**
 * CST-126 Blog project
 * login.php version 1.0
 * Program Author: Evan Wilson, Branden Manibusan, and Nicholas Thomas
 * Date: 10/14/2018
 * HTML and PHP use in formatting and display of login page and associated errors
 * References: https://codewithawa.com/posts/complete-user-registration-system-using-php-and-mysql-database
 * Site was used in initial development of application with many changes implemented.
 */
include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
    <title>CST 126 Blog</title>
    <!-- uses current system time in style.css call to ensure current updates without browser cache-->
    <link rel="stylesheet" type="text/css" href="css/style.css?<?php echo time(); ?>">
</head>
<body>
<div class="header">
    <h2>Login to CST-126 Blog</h2>
</div>

<form method="post" action="login.php">
    <?php include('errors.php'); ?>
    <div class="flex-container">
        <div class="input-group">
            <label>Username</label>
            <input type="text" name="username" required="true">
        </div>
    </div>
    <div class="flex-container">
        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" required="true">
        </div>
    </div>
    <div class="flex-container">
        <div class="input-group">
            <button type="submit" class="btn" name="login_user">Login</button>
        </div>
    </div>
</form>
<div class="footer">

    <p>
        New to the blog? <a href="register.php" style="color: white;">Sign up</a>
    </p>

</div>
</body>
</html>