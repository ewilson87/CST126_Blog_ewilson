<?php
/**
 * CST-126 Blog project
 * new_topic.php version 1.0
 * Program Author: Evan Wilson, Branden Manibusan, and Nicholas Thomas
 * Date: 10/14/2018
 * Allow users to post on a topic, also including a basic language filter.
 * References: https://codewithawa.com/posts/complete-user-registration-system-using-php-and-mysql-database
 * Site was used in initial development of application with many changes implemented.
 */
include('alert.php'); // Include custom alert script
include('server.php');

    //only allows access to this page if logged in 
    if (!isset($_SESSION['username'])) {
        $_SESSION['msg'] = "You must log in first";
        session_destroy();
        unset($_SESSION['username']);
        header('location: login.php');
    }

    $badWordBool = false;
?>

<!DOCTYPE html>
<html>
<head>
    <title>CST 126 Blog</title>
    <!-- uses current system time in style.css call to ensure current updates without browser cache-->
    <link rel="stylesheet" type="text/css" href="css/style.css?<?php echo time(); ?>">
    <style>
        <!-- Alert custom style -->
        <?php include 'css/alert.css'; ?>

        #modalContainer {
            background-color: rgba(0, 0, 0, 0.3);
            position: absolute;
            top: 0;
            width: 100%;
            height: 100%;
            left: 0px;
            z-index: 10000;
        }

        #forum {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }
    </style>
    <!-- Language Filter -->
    <script type="text/javascript">
        function language_filter(el){
            let text_area = document.getElementById(el);
            let regex = /death|murder|kill|dead/gi;

            if (text_area.value.search(regex) > -1) {
                text_area.value = text_area.value.replace(regex, "");
                window.alert("The word you have entered has been removed, due to inappropriate language."); // Custom alert

            }
        }
    </script>
</head>
<body>
<div class="header">
    <a class="logo">Reply to:  <?php echo $_SESSION['temptopic'];?></a>
    <div class="header-right">
        <a class="active" href="index.php">Home</a>
        <a class="active" href="home_forum.php?refresh='1'">Forum</a>
        <a class="active" href="topic_forum.php?topic=<?php echo $_SESSION['temptopic']?>">Back</a>
        <a href="index.php?logout='1'">Logout</a>
    </div>
</div>


<form method="post" action="reply_topic.php">
    <?php include('errors.php'); ?>
    <a>
        <div class="input-group">
            <br>
            <!-- Implement language filter in body of reply -->
            <textarea
                class="text" id="text"
                onkeyup="language_filter('text')"
                placeholder="What do you want to say? Write Some Text Having Words 'death', 'kill', 'murder', 'dead'"
                name="message" maxlength="5000" rows="20" cols="160"
                style="
                padding: 5px 10px;
                font-size: 18px;
                font-weight: bold;
                border-radius: 5px;
                border: 1px #dddddd;
                background-color: #dddddd;
                color: dodgerblue;
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
            <button type="submit" class="btn" id="postButton" name="reply_topic">POST</button>
        </div>
    </div>

</form>
</body>
</html>