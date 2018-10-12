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
    <h2>Forum</h2>
</div>

<form method="post" action="new_topic.php?loggedin='1'">
    future code here to make clicking on the table direct you to all posts on that topic
<table id="forum"><col width="80%"><col width="20%"><tr><th>Topic</th><th>Time of most recent post</th></tr>

        <?php
echo $_SESSION['mainforum'];
    ?>

    </table>
        <!-- edit this to use for future clicks to work as links? -->
        <script language="javascript" type="text/javascript">
            function rowClick(row) {
                var x = row.cells;

                alert(x[0].innerHTML);
            }
        </script>

        <br>
        <div class="input-group">
                <button type="submit" class="btn" name="new_topic">New Topic</button>
            </div>
</form>
</body>
<div class="footer">
    <p style="text-align:left;"><a href="index.php" style="color: white;">HOME</a>
        <span style="float:right;"><a href="index.php?logout='1'" style="color: white;">LOGOUT</a></span>
    </p>
</div>
</html>