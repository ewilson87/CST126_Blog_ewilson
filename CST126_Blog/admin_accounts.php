<?php
/**
 * CST-126 Blog project
 * admin_accounts.php version 1.0
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
//@ $_SESSION['refreshAccounts'] = $_GET['refreshAccounts'];

//if (isset($_GET['refreshAccounts'])){
//calls server.php again now that $_SESSION['refreshAccounts'] is set 
//@ include('server.php');
//}
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
            color: #212121;
        }

        #forum tr:nth-child(even) {
            border: 1px solid dodgerblue;
            color: #212121;}
        }

        #forum th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            border: 2px solid;
            border-color: dodgerblue;
            background-color: rgba(0, 0, 0, 0.0);
            color: #212121;
            font-size: 18px;
            font-weight: bold;
        }
        tr:hover {
            border: 1px #dddddd;
            background-color: #dddddd;
            color: dodgerblue;
        }    }

    </style>

    <!-- This stops the enter key from being active and forces user to click which button to submit -->
    <script type="text/javascript"> 
        function stopRKey(evt) { 
        var evt = (evt) ? evt : ((event) ? event : null); 
        var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
        if ((evt.keyCode == 13) && (node.type=="text"))  {return false;} 
        } 
        document.onkeypress = stopRKey; 
    </script>
</head>
<body>
<!-- Implement new header -->
<div class="header">
    <a href="#default" class="logo">ADMINISTRATION OF USER ACCOUNTS</a>
    <div class="header-right">
        <a class="active" href="index.php">Home</a>
        <a href="index.php?logout='1'">Logout</a>
    </div>
</div>
<?php 
        if (isset($_SESSION['suspendSuccess'])){
            echo "<br>";
            if ($_SESSION['suspendSuccess'] == true){
                echo "<strong>ACCOUNT CHANGE SUCCESSFUL.</strong>";
            }
            else {
                echo "<strong>ACCOUNT CHANGE NOT SUCCESSFUL. PLEASE CHECK THE USERNAME AND TRY AGAIN. </strong>";
                echo "<br>";
                echo "Invalid USERNAME entered: ";
                echo $_SESSION['queryAccountUsername'];
            }
            echo "<br>";
            unset($_SESSION['suspendSuccess']);
        }
    ?>

<form method="post" action="admin_accounts.php">
    <table id="forum">
        <tr><th style="border: 1px solid rgba(255, 255, 255, 0.3); font-weight: bold; background-color: #dddddd; color: dodgerblue">USERNAME</th>
            <th style="border: 1px solid rgba(255, 255, 255, 0.3); font-weight: bold; background-color: #dddddd; color: dodgerblue">FIRST NAME</th>
            <th style="border: 1px solid rgba(255, 255, 255, 0.3); font-weight: bold; background-color: #dddddd; color: dodgerblue">LAST NAME</th>
            <th style="border: 1px solid rgba(255, 255, 255, 0.3); font-weight: bold; background-color: #dddddd; color: dodgerblue">E-MAIL</th>
            <th style="border: 1px solid rgba(255, 255, 255, 0.3); font-weight: bold; background-color: #dddddd; color: dodgerblue">MEMBER SINCE</th>
            <th style="border: 1px solid rgba(255, 255, 255, 0.3); font-weight: bold; background-color: #dddddd; color: dodgerblue">SUSPENDED</th>
            <th style="border: 1px solid rgba(255, 255, 255, 0.3); font-weight: bold; background-color: #dddddd; color: dodgerblue">ACCESS LVL</th></tr>

        <?php
        echo $_SESSION['accountsList'];
        ?>

    </table>

    <br>
    <br>
    </form>
    <form method="post" action="admin_accounts.php">
    <div class="flex-container">
    <div class="input-group">
        
                <input type="text" name="accountUsername" placeholder="USERNAME">
                <br>
                <br>
        <button type="submit" class="btn" name="suspendAccount">SUSPEND</button>            
        <button type="submit" class="btn" name="enableAccount">ENABLE</button>
        <br>
        <br>
        <button type="submit" class="btn" name="moderatorAccount">MAKE MODERATOR</button>
        <button type="submit" class="btn" name="userAccount">MAKE USER</button>
        <br>
        <br>
        <button type="submit" class="btn" name="deleteAccount">DELETE</button>
        <label>WARNING: DELETE CANNOT BE UNDONE</label>
    </div>

    </div>
</form>
</body>
</html>