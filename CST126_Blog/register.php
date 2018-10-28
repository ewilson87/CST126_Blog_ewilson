<?php
/**
 * CST-126 Blog project
 * register.php version 1.0
 * Program Author: Evan Wilson, Branden Manibusan, and Nicholas Thomas
 * Date: 10/14/2018
 * HTML and PHP use in formatting and display of registration page and associated errors
 * References: https://codewithawa.com/posts/complete-user-registration-system-using-php-and-mysql-database
 * Site was used in initial development of application with many changes implemented.
 */
include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
    <title>Register New User Account</title>
    <!-- uses current system time in style.css call to ensure current updates without browser cache-->
    <link rel="stylesheet" type="text/css" href="css/style.css?<?php echo time(); ?>">
    <div class="header">
        <a href="#default" class="logo">Register New User Account</a>
        <div class="header-right">
            <a class="active" href="login.php">Sign In</a>
        </div>
    </div>
    <!-- Language Filter -->
    <script type="text/javascript">
        function language_filter(el){
            var text_area = document.getElementById(el);
            var regex = /death|murder|kill|dead/gi;
            if(text_area.value.search(regex) > -1) {
                text_area.value = text_area.value.replace(regex, "");
            }
        }
    </script>
</head>
<body>

<!-- Basic input options, use flex-containers and input-groups for display formatting -->
<form method="post" action="register.php">
    <?php include('errors.php'); ?>
    <div class="flex-container">
        <div class="input-group">
            <label>First Name</label>
            <input type="text" name="fname" value="<?php echo $fname; ?>" required="true">
        </div>
        <div class="input-group">
            <label>Last Name</label>
            <input type="text" name="lname" value="<?php echo $lname; ?>" required="true">
        </div>
    </div>
    <div class="flex-container">
        <div class="input-group">
            <label>Username</label>
            <!-- Implement language filter in username -->
            <input
                    class="ta" id="ta" name="ta"
                    onkeyup="language_filter('ta')" onkeydown="language_filter('ta')"
                    type="text" name="username" value="<?php echo $username; ?>" required="true">
        </div>
        <div class="input-group">
            <label>E-mail</label>
            <input type="email" name="email" value="<?php echo $email; ?>" required="true">
        </div>
    </div>
    <div class="flex-container">
        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password_1" required="true">
        </div>
        <div class="input-group">
            <label>Confirm password</label>
            <input type="password" name="password_2" required="true">
        </div>
    </div>
    <div class="flex-container">
        <div class="input-group">
            <button type="submit" class="btn" name="reg_user">Register</button>
        </div>
    </div>

</form>
<!-- TODO: Remove when everyone test
<div class="footer">
    <p>
        Already registered? <a href="login.php" style="color: white;">Sign in</a>
    </p>
</div>
-->
</body>
</html>