<?php
/**
 * Created by PhpStorm.
 * User: ewwil
 * Date: 10/9/2018
 * Time: 4:31 PM
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