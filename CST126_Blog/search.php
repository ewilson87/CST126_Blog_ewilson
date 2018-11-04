<?php
/**
 * CST-126 Blog project
 * search.php version 1.0
 * Program Author: Evan Wilson, Branden Manibusan, and Nicholas Thomas
 * Date: 11/04/2018
 * Displays search results
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
    <a class="logo">Search Forum</a>
    <div class="header-right">
        <a class="active" href="index.php">Home</a>
        <a class="active" href="home_forum.php?refresh='1'">Forum</a>
        <a href="index.php?logout='1'">Logout</a>
    </div>
</div>
<form method="post" action="search_results.php">
    <br>
    <br>
    <div class="flex-container">
        <div class="input-group">
            <input type="text" name="searchTerm" placeholder="SEARCH TERM">
            <br>
            <br>
            <div class="radio">
                <input type="radio" name="searchBy" value="username" checked="checked"> Username<br>
                <input type="radio" name="searchBy" value="topic"> Topic<br>
                <input type="radio" name="searchBy" value="message"> Message<br>
            </div>
            <br>
            <br>
            <button type="submit" class="btn" name="searchForum">Search</button>
        </div>
    </div>
</form>
</body>
</html>