<?php
/**
 * CST-126 Blog project
 * new_topic.php version 1.0
 * Program Author: Evan Wilson, Branden Manibusan, and Nicholas Thomas
 * Date: 10/14/2018
 * Allow user to create new topic and start thread with first post
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
    <!-- Language Filter -->
    <script type="text/javascript">
        function check_val()
        {
            var bad_words=new Array("death","kill","murder");
            var check_text=document.getElementById("text").value;

            var error=0;
            for(var i=0;i<bad_words.length;i++)
            {
                var val=bad_words[i];
                if((check_text.toLowerCase()).indexOf(val.toString())>-1)
                {
                    error=error+1;
                }
            }

            if(error>0)
            {
                document.getElementById("bad_notice").innerHTML="WARNING: Some Bad Words In Your Text";
            }
            else
            {
                document.getElementById("bad_notice").innerHTML="";
            }
        }
    </script>
</head>
<body>
<div class="header">
    <h2>New Topic Post</h2>
</div>

<form method="post" action="new_topic.php">
    <?php include('errors.php'); ?>
    <a>
        <div class="input-group">
            <input placeholder="Enter Topic Title" type="text" name="topic" value="<?php echo $topic; ?>" required="true">
        </div>
    </a>
    <a>
        <div class="input-group">
            <br>
            <textarea
                placeholder="What do you want to say? Write Some Text Having Words 'death', 'kill', 'murder'"
                id="text" onKeyUp="check_val()"
                name="message" maxlength="5000" rows="20" cols="160"
                style="
                border: 1px rgba(0, 0, 0, 0.85);
                background-color: rgba(0, 0, 0, 0.5);
                color: #BBFFF1;
                padding: 5px 10px;
                font-size: 16px;
                border-radius: 5px;"
                value="<?php echo $message; ?>"
                required="true"></textarea>
            <p id="bad_notice"></p>
            <br>
            <p style="font-size: 14px;">Max 5000 characters</p>
        </div>
    </a>
    <br>
    <div class="flex-container">
        <div class="input-group">
            <button type="submit" class="btn" name="new_topic2">POST</button>
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