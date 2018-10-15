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
//$_SESSION['refresh'] = $_GET['refresh'];
//calls server.php again now that $_SESSION['refresh'] is set 
@ include('server.php');
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
            width: 100%;
        }

        #forum td, #forum th {
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 8px;
            color: #ffffff;
        }

        #forum tr:nth-child(even) {
            border: 1px solid rgba(255, 255, 255, 0.3);
            background-color: rgba(0, 0, 0, 0.5);
            color: #BBFFF1;}
        }

        #forum th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            border: 1px solid #BBFFF1;
            background-color: #000000;
            color: #FFFFFF;
        }
        td:hover {
            border: 1px solid rgba(255, 255, 255, 0.3);
            background-color: rgba(0, 0, 0, 0.5);
            color: #BBFFF1;
        }    }

    </style>
</head>
<body>
<div class="header">
    <h2>Forum</h2>
</div>

<form method="post" action="new_topic.php?<?php echo time(); ?>">
    <table id="forum">
        <col width="80%"><col width="20%">
        <tr><th style="border: 1px solid #BBFFF1; background-color: #000000; color: #FFFFFF;">Topic (most recent on top)</th>
            <th style="border: 1px solid #BBFFF1; background-color: #000000; color: #FFFFFF;">Time of most recent post</th></tr>

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
    <div class="flex-container">
        <div class="input-group">
            <button type="submit" class="btn" name="new_topic1">New Topic</button>
        </div>
    </div>
</form>
</body>
<div class="footer">
    <p style="text-align:left;"><a href="index.php" style="color: white;">HOME</a>
        <span style="float:right;"><a href="index.php?logout='1'" style="color: white;">LOGOUT</a></span>
    </p>
</div>
</html>