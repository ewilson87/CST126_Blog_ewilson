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



if (isset($_GET['refreshAccounts'])){
//forces accounts to refresh the table after changes made
@ $_SESSION['refreshAccounts'] = $_GET['refreshAccounts'];
//calls server.php again now that $_SESSION['refreshAccounts'] is set 
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
    <a class="logo">ADMINISTRATION OF USER ACCOUNTS</a>
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
        <tr><th style="border-bottom: 3px solid dodgerblue; padding-left: 20px; font-size: 22px; font-weight: bold; background-color: #FFFFFF; color: dodgerblue">USERNAME</th>
            <th style="border-bottom: 3px solid dodgerblue; padding-left: 20px; font-size: 22px; font-weight: bold; background-color: #FFFFFF; color: dodgerblue">FIRST NAME</th>
            <th style="border-bottom: 3px solid dodgerblue; padding-left: 20px; font-size: 22px; font-weight: bold; background-color: #FFFFFF; color: dodgerblue">LAST NAME</th>
            <th style="border-bottom: 3px solid dodgerblue; padding-left: 20px; font-size: 22px; font-weight: bold; background-color: #FFFFFF; color: dodgerblue">E-MAIL</th>
            <th style="border-bottom: 3px solid dodgerblue; padding-left: 20px; font-size: 22px; font-weight: bold; background-color: #FFFFFF; color: dodgerblue">MEMBER SINCE</th>
            <th style="border-bottom: 3px solid dodgerblue; padding-left: 20px; font-size: 22px; font-weight: bold; background-color: #FFFFFF; color: dodgerblue">SUSPENDED</th>
            <th style="border-bottom: 3px solid dodgerblue; padding-left: 20px; font-size: 22px; font-weight: bold; background-color: #FFFFFF; color: dodgerblue">ACCESS LVL</th></tr>

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
                <div class="radio">
                <input type="radio" name="accountAction" value="suspendAccount" checked="checked"> Suspend Account<br>
                <input type="radio" name="accountAction" value="enableAccount"> Enable Account<br>
                <input type="radio" name="accountAction" value="moderatorAccount"> Make Moderator<br>
                <input type="radio" name="accountAction" value="userAccount"> Remove Moderator<br>
                <input type="radio" name="accountAction" value="deleteAccount"> Delete Account<br>
            </div>
                <button type="submit" class="btn" name="changeAccount">Submit</button>
        <!--
        <button type="submit" class="btn" name="suspendAccount">SUSPEND</button>            
        <button type="submit" class="btn" name="enableAccount">ENABLE</button>
        <br>
        <br>
        <button type="submit" class="btn" name="moderatorAccount">MAKE MODERATOR</button>
        <button type="submit" class="btn" name="userAccount">MAKE USER</button>
        <br>
        <br>
        <button type="submit" class="btn" name="deleteAccount">DELETE</button>
    -->
        <label>WARNING: DELETE CANNOT BE UNDONE</label>
    
    </div>

    </div>
</form>
</body>
</html>