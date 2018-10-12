<?php
/**
 * CST-126 Blog project
 * home_forum.php version 1.0
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

    //sets session variable topic to whatever topic was selected from home forum
    $_SESSION['topic'] = $_GET['topic'];

    //calls server.php again now that $_SESSION['topic'] is set to 
   @ include('server.php');
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
    <h2><?php echo $_SESSION['temptopic'] ?> Forum</h2>
</div>

<form method="post" action="reply_topic.php?topic='<?php echo $_SESSION['temptopic']?>'">
<table id="forum"><col width="20%"><col width="60%"><col width="20%"><tr><th>Username</th><th>Message</th><th>Timestamp</th></tr>

        <?php
echo $_SESSION['topicforum'];
    ?>

    </table>

        <br>
        <div class="flex-container">
        <div class="input-group">
                <button type="submit" class="btn" name="reply_topic1">REPLY</button>
            </div>
</div>
</form>
</body>
<div class="footer">
<p style="text-align:left;"><a href="home_forum.php?refresh='1'" style="color: white;">BACK</a>
        <span style="float:right;"><a href="index.php?logout='1'" style="color: white;">LOGOUT</a></span>
    </p>
</div>
</html>