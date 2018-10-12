<?php
/**
 * CST-126 Blog project
 * new_topic.php version 1.0
 * Program Author: Evan Wilson
 * Date: 10/10/2018
 * UPDATE THIS LATER
 * References: https://codewithawa.com/posts/complete-user-registration-system-using-php-and-mysql-database
 * Site was used in initial development of application with many changes implemented.
 */

include('server.php');

    //only allows access to this page if logged in 
    if (!isset($_SESSION['username'])) {
        $_SESSION['msg'] = "You must log in first";
        session_destroy();
        unset($_SESSION['username']);
        header('location: login.php');
    }


?>

<!DOCTYPE html>
<html>
<head>
    <title>CST 126 Blog</title>
    <link rel="stylesheet" type="text/css" href="style.css?<?php echo time(); ?>">
    <style>
    #forum {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

#forum td, #forum th {
    border: 1px solid #ddd;
    padding: 8px;
}

#forum tr:nth-child(even) {
    background-color: #f2f2f2;
}

#forum tr:hover {
    background-color: #ddd;
}

#forum th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #466ac6;
    color: white;
}
</style>
</head>
<body>
<div class="header">
    <h2>Reply to:  <?php echo $_SESSION['temptopic'];?></h2>
</div>

<form method="post" action="reply_topic.php">
    <?php include('errors.php'); ?>
    <a>
        <div class="input-group">
            <br>
            <label><strong>What do you want to say?</strong></label>
            <textarea name="message" maxlength="5000" rows="20" cols="160" 
                style="
                    padding: 5px 10px;
                    font-size: 16px;
                    border-radius: 5px;
                    border: 1px solid gray;" 
                value="<?php echo $message; ?>"
                required="true"></textarea>
            <br>
            <p style="font-size: 14px;">Max 5000 characters</p>
        </div>
    </a>
        <br>
    <div class="flex-container">
        <div class="input-group">
            <button type="submit" class="btn" name="reply_topic">POST</button>
        </div>
    </div>
</form>
</body>
<div class="footer">
    <p style="text-align:left;"><a href="topic_forum.php?topic='<?php echo $_SESSION['temptopic']?>'" style="color: white;">BACK</a>
        <span style="float:right;"><a href="index.php?logout='1'" style="color: white;">LOGOUT</a></span>
    </p>
</div>
</html>