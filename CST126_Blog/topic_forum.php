<?php
/**
 * CST-126 Blog project
 * topic_forum.php version 1.0
 * Program Author: Evan Wilson, Branden Manibusan, and Nicholas Thomas
 * Date: 10/14/2018
 * Topic post and reply function
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
if (isset($_GET['topic'])){
@ $_SESSION['topic'] = $_GET['topic'];

//calls server.php again now that $_SESSION['topic'] is set to
@ include('server.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CST 126 Blog</title>
    <!-- uses current system time in style.css call to ensure current updates without browser cache-->
    <link rel="stylesheet" type="text/css" href="css/style.css?<?php echo time(); ?>">
    <style>
        #forum {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            text-align: left;
            width: 100%;
        }
        #forum td, #forum th {
            border: 1px solid rgba(255, 255, 255, 255);
            padding-left: 20px;
            text-align: left;
            color: black;
        }
        #forum tr:nth-child(even) {
            background-color: #ffffff;
            text-align: left;
            color: #212121;
        }
        #forum th {
            border-bottom: 3px solid dodgerblue;
            padding-left: 20px;
            font-size: 22px;
            font-weight: bold;
            background-color: #FFFFFF;
            color: dodgerblue;
            text-align: left;
        }
        td:hover {
            border: 1px #dddddd;
            background-color: dodgerblue;
        }

    </style>
</head>
<body>
<div class="header">
    <!-- Implement new header -->
    <div class="header-right">
        <a class="active" href="home_forum.php?refresh='1'">Forum</a>
        <a href="index.php?logout='1'">Logout</a>
    </div>

    <!-- Needed to style header title -->
    <h2 style="
        float: left;
        color: white;
        padding: 12px;
        text-decoration: none;
        font-size: 40px;
        line-height: 25px;
        border-radius: 4px;
        font-weight: bolder;
    ">
    Forum Topic: <?php echo $_SESSION['temptopic'] ?></h2>
    <?php
        if (isset($_SESSION['deleteSuccess'])){
            echo "<br>";
            if ($_SESSION['deleteSuccess'] == true){
                echo "<strong>POST DELETED SUCCESSFULLY.</strong>";
            }
            else {
                echo "<strong>POST NOT DELETED. PLEASE CHECK THE POST ID AND TRY AGAIN. </strong>";
                echo "<br>";
                echo "Invalid Post ID entere: ";
                echo $_SESSION['queryPostID'];
                echo "<br>";
                echo $_SESSION['postID'];
            }
            echo "<br>";
            unset($_SESSION['deleteSuccess']);
        }
    ?>
</div>

<!-- Passes currently selected topic to the next page via URL to ensure correct topic to reply to-->
<form style="font-size: 22px; font-weight: bold;"
        method="post" action="topic_forum.php?topic=<?php echo $_SESSION['temptopic']?>">


    <?php
    //sets table up for admin/moderator mode
        if ($_SESSION['access_lvl'] > 0):
    ?>
    <!-- Sets table, then echos query results from server.php for all the rows data contained in $_SESSION['topicforum']-->
    <table id="forum"><col width="15%"><col width="60%"><col width="15%"><col width="10%"><tr ><th>Username</th><th>Message</th><th>Timestamp</th><th>Post ID</th></tr>
        <?php
        if ($_SESSION['access_lvl'] == 2) {
            echo $_SESSION['topicforum'] . " ADMIN MODE";

        }
        else {
            echo $_SESSION['topicforum'] . " MODERATOR MODE";
        }
        ?>
    </table>

    <?php
        else:
    ?>
    <!-- Sets table, then echos query results from server.php for all the rows data contained in $_SESSION['topicforum']-->
    <table id="forum"><col width="20%"><col width="60%"><col width="20%"><tr><th>Username</th><th>Message</th><th>Timestamp</th></tr>
        <?php
        echo $_SESSION['topicforum'];
        ?>
    </table>
        <?php endif; ?>


    <br>

    <div class="flex-container">
        <div class="input-group">
            <button type="submit" class="btn" name="reply_topic1">REPLY</button>
        </div>
    </div>
</form>
<?php if ($_SESSION['access_lvl'] > 0): ?>
    <form method="post" action="topic_forum.php?topic=<?php echo $_SESSION['temptopic']?>">
        <div class="flex-container">
            <div class="input-group">
                <label>Post ID to delete - WARNING: CANNOT BE UNDONE</label>
                <input type="text" name="postID">
                <button type="submit" class="btn" name="deletePost">DELETE</button>
            </div>
        </div>
        <br>
    </form>
<?php endif ?>
</body>
</html>