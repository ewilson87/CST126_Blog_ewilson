<?php
/**
 * CST-126 Blog project
 * login.php version 1.0
 * Program Author: Evan Wilson
 * Date: 10/9/2018
 * HTML and PHP use in formatting and display of login page and associated errors
 * References: https://codewithawa.com/posts/complete-user-registration-system-using-php-and-mysql-database
 * Site was used in initial development of application with many changes implemented.
 */
include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
    <title>CST 126 Blog</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="header">
    <h2>Login</h2>
</div>

<form method="post" action="login.php">
    <?php include('errors.php'); ?>
    <div class="flex-container">
        <div class="input-group">
            <label>Username</label>
            <input type="text" name="username" >
        </div>
    </div>
    <div class="flex-container">
        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password">
        </div>
    </div>
    <div class="flex-container">
        <div class="input-group">
            <button type="submit" class="btn" name="login_user">Login</button>
        </div>
    </div>
    <div class="flex-container">
        <p>
            New to the blog? <a href="register.php">Sign up</a>
        </p>
    </div>
</form>
</body>
</html>