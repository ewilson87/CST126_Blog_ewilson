<?php
/**
 * CST-126 Blog project
 * server.php version 1.0
 * Program Author: Evan Wilson, Branden Manibusan, and Nicholas Thomas
 * Date: 10/14/2018
 * The main logic in connecting to the database for registration and login
 * References: https://codewithawa.com/posts/complete-user-registration-system-using-php-and-mysql-database
 * Site was used in initial development of application with many changes implemented.
 */

session_start();

// initializing variables
$username = "";
$fname    = "";
$lname    = "";
$email    = "";
$datejoined = "";
$topic = "";
$message = "";
$errors = array();

//database login details obtained from GCU Hosting Solution (Heroku)
$host = 'us-cdbr-iron-east-01.cleardb.net';
$user = 'b1d8c8c0bbb430';
$dbpassword = 'c95a08da';
$database = 'heroku_900c33d3127dd0b';

// connect to the database using above login details
$db = mysqli_connect($host, $user, $dbpassword, $database);

// REGISTER USER
if (isset($_POST['reg_user'])) {
    // receive all input values from the form
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $fname = mysqli_real_escape_string($db, $_POST['fname']);
    $lname = mysqli_real_escape_string($db, $_POST['lname']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

    // form validation: ensure that the form is correctly filled out
    // by adding (array_push()) corresponding error into $errors array
    if (empty($username)) { array_push($errors, "Username is required"); }
    if (empty($fname)) { array_push($errors, "First name is required"); }
    if (empty($lname)) { array_push($errors, "Last name is required"); }
    if (empty($email)) { array_push($errors, "Email is required"); }
    if (empty($password_1)) { array_push($errors, "Password is required"); }
    if (empty($password_2)) { array_push($errors, "Confirm password is required"); }
    if ($password_1 != $password_2) {
        array_push($errors, "The two passwords do not match");
    }

    // first check the database to make sure
    // a user does not already exist with the same username and/or email
    $user_check_query = "SELECT * FROM accounts WHERE username='$username' OR email='$email' LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) { // if user exists
        if ($user['username'] === $username) {
            array_push($errors, "Username already exists");
        }

        if ($user['email'] === $email) {
            array_push($errors, "That e-mail is already used");
        }
    }

    // Register user if there are no errors, table also creates a default current date for when all users are registered to track when they joined
    if (count($errors) == 0) {
        $password = md5($password_1); //encrypts password before saving to database
        $query = "INSERT INTO accounts (username, password, fname, lname, email, datejoined) 
  			  VALUES('$username', '$password', '$fname', '$lname', '$email', CURRENT_DATE)";
        mysqli_query($db, $query);
        //sets session variables for user for possible use in the future
        $_SESSION['username'] = $username;
        $_SESSION['fname'] = $fname;
        $_SESSION['lname'] = $lname;
        $_SESSION['email'] = $email;
        $_SESSION['success'] = "You are now logged in";
        header('location: index.php');
    }
}

// LOGIN USER
if (isset($_POST['login_user'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    //Currently displays user's first name upon logging in
    //Queries SQL to verify a match for username and password
    if (count($errors) == 0) {
        $password = md5($password); //encrypts password to compare to database
        $query = "SELECT * FROM accounts WHERE username='$username' AND password='$password'";
        $results = mysqli_query($db, $query);

        if (mysqli_num_rows($results) == 1) {
            $row=mysqli_fetch_array($results);
            //sets session variables for user for use later in app areas
            $_SESSION['fname'] = $row['fname'];
            $_SESSION['lname'] = $row['lname'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['username'] = $username;
            $_SESSION['success'] = "You are now logged in";
            header('location: index.php');
        }else {
            array_push($errors, "Wrong username/password combination");
        }
    }
}

// load forum when coming from index.php button click or topic_forum.php link with the refresh set.
if (isset($_POST['main_forum']) or isset($_SESSION['refresh'])) {
    //query groups the forum table by title and orders it by the most recent posts at top
    $query = "SELECT title, post_time from forum f1 where post_time = (select MAX(post_time) from forum f2 where f1.title = f2.title) group by title order by post_time desc";
    $results = mysqli_query($db, $query) or die("Bad Query: $query");
    unset($_SESSION['refresh']);
    $mainForum = '';
    // goes through the query row by row and concatanates with itself to make the table
    while ($row = mysqli_fetch_assoc($results)) {
        $mainForum = $mainForum.'<tr onclick="javascript:rowClick(this)">'."<td>{$row['title']}</td><td>{$row['post_time']}</td></tr>";
    }

    $_SESSION['mainforum'] = $mainForum;
}

// load topic forum
if (isset($_SESSION['topic'])) {
    $title = $_SESSION['topic'];
    //copies topic to temptopic for use later in the app without impacting this are by letting the topic variable be unset, otherwise it will be called when we don't want it to.
    $_SESSION['temptopic'] = $title;
    unset($_SESSION['topic']);
    $query = "SELECT * from forum where title = '$title' order by post_time desc";
    $results = mysqli_query($db, $query) or die("Bad Query: $query");

    $topicforum = '';
    // goes through the query row by row and concatanates with itself to make the table
    while ($row = mysqli_fetch_assoc($results)) {
        $topicforum = $topicforum."<tr><td style='text-align:center'>{$row['username']}</td>
                                           <td>{$row['message']}</td>
                                           <td style='text-align:center'>{$row['post_time']}</td></tr>";
    }

    $_SESSION['topicforum'] = $topicforum;
}

// New topic post
if (isset($_POST['new_topic2'])) {
    // receive all input values from the form
    $topic = mysqli_real_escape_string($db, $_POST['topic']);
    $message = mysqli_real_escape_string($db, $_POST['message']);
    $username = $_SESSION['username'];
    //ensure errors array is empty before possible errors pushed
    $errors = array();
    // form validation: ensure that the form is correctly filled out
    // by adding (array_push()) corresponding error into $errors array
    if (empty($topic)) { array_push($errors, "Topic is required"); }
    if (empty($message)) { array_push($errors, "Message is required"); }

    if (count($errors) == 0) {
        $query = "INSERT INTO forum (username, title, message, post_time) values ('$username', '$topic', '$message', CURRENT_TIMESTAMP)";
        $results = mysqli_query($db, $query);
        header("location: home_forum.php?newpost='1'");
    }

}

// Post reply to existing topic
if (isset($_POST['reply_topic'])) {
    // receive all input values from the form
    $topic = $_SESSION['temptopic'];
    $message = mysqli_real_escape_string($db, $_POST['message']);
    $username = $_SESSION['username'];
    //ensure errors array is empty before possible errors pushed
    $errors = array();
    // form validation: ensure that the form is correctly filled out
    // by adding (array_push()) corresponding error into $errors array
    if (empty($message)) { array_push($errors, "Message is required"); }

    if (count($errors) == 0) {
        $query = "INSERT INTO forum (username, title, message, post_time) values ('$username', '$topic', '$message', CURRENT_TIMESTAMP)";
        $results = mysqli_query($db, $query);
        header('location: topic_forum.php?topic='.$_SESSION['temptopic']);
    }

}
?>