<?php
/**
 * CST-126 Blog project
 * home_forum.php version 1.0
 * Program Author: Evan Wilson, Branden Manibusan, and Nicholas Thomas
 * Date: 10/14/2018
 * Display all the forums created by users
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

//forces home forum to refresh the table if accessed from the back link instead of a button
if (isset($_GET['refresh'])){
@ $_SESSION['refresh'] = $_GET['refresh'];
//calls server.php again now that $_SESSION['refresh'] is set 
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
            font-weight: bold;
            width: 100%;
        }

        #forum td, #forum th {
            border: 1px solid #FFFFFF;
            padding: 20px;
            color: black;
        }

        #forum tr:nth-child(even) {
            border: 1px solid #FFFFFF;
            color: black;}
        }

        #forum th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            border: 2px solid;
            border-color: #FFFFFF;
            background-color: rgba(0, 0, 0, 0.0);
            color: black;
            font-size: 18px;
            font-weight: bold;
        }
        td:hover {
            border: 1px #dddddd;
            background-color: dodgerblue;
        }
     }

    </style>
</head>
<body>
<div class="header">
    <a class="logo">Forum</a>
    <div class="header-right">
        <a class="active" href="index.php">Home</a>
        <a class="active" href="new_topic.php">New Topic</a>
        <a class="active" href="search.php">Search</a>
        <a href="index.php?logout='1'">Logout</a>
    </div>
</div>
<form method="post" action="new_topic.php?<?php echo time(); ?>">
    <table id="forum">
        <col width="80%"><col width="20%">
        <tr><th style="border-bottom: 3px solid dodgerblue; padding-left: 20px; font-size: 22px; font-weight: bold; background-color: #FFFFFF; color: dodgerblue">Topic (most recent on top)</th>
            <th style="border-bottom: 3px solid dodgerblue; padding-left: 20px; font-size: 22px; font-weight: bold; background-color: #FFFFFF; color: dodgerblue">Time of most recent post</th></tr>

        <?php
        echo $_SESSION['mainforum'];
        ?>

    </table>
    <!-- Javascript to forward to next page and set topic -->
    <!-- Passes currently selected topic to the next page via URL to ensure correct topic to reply to since can't assign PHP variable from within Javascript function-->
    <script language="javascript" type="text/javascript">
        function rowClick(row) {
            var x = row.cells;
            x = x[0].innerHTML;
            window.location.href = `topic_forum.php?topic=${x}`;
        }
    </script>

    <br>
    <!-- remove these buttons after header buttons tested more
    <div class="flex-container">
        <div class="input-group">
            <button type="submit" class="btn" name="new_topic1">New Topic</button>
        </div>
    </div>
</form>
<form method="post" action="search.php?">
    <div class="flex-container">
        <div class="input-group">
            <button type="submit" class="btn" id="searchButton" name="search">SEARCH</button>
        </div>
    </div>
    -->
</form>
</body>
<!-- TODO: Remove when everyone test
<div class="footer">
    <p style="text-align:left;"><a href="index.php" style="color: white;">HOME</a>
        <span style="float:right;"><a href="index.php?logout='1'" style="color: white;">LOGOUT</a></span>
    </p>
</div>
-->
</html>