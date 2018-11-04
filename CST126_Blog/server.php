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
$access_lvl = "";
$errors = array();
$postID = "";

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
        $query = "INSERT INTO accounts (username, password, fname, lname, email, datejoined, access_lvl) 
  			  VALUES('$username', '$password', '$fname', '$lname', '$email', CURRENT_DATE, 0)";
        mysqli_query($db, $query);
        //sets session variables for user for possible use in the future
        $_SESSION['username'] = $username;
        $_SESSION['fname'] = $fname;
        $_SESSION['lname'] = $lname;
        $_SESSION['email'] = $email;
        $_SESSION['access_lvl'] = $access_lvl;
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
            $_SESSION['suspended'] = $row['suspended'];
            $_SESSION['access_lvl'] = $row['access_lvl'];
            if ($_SESSION['suspended'] == 1){
                array_push($errors, "Your account is currently suspended by the administrator");
                session_destroy();
                unset($_SESSION['username']);
            }
            else {
            $_SESSION['success'] = "You are now logged in";
            header('location: index.php');
            }
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
        header("location: home_forum.php?refresh='1'");
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
    }
    header('location: topic_forum.php?topic='.$_SESSION['temptopic']); 
}

if (isset($_POST['deletePost'])) {
    $postID = mysqli_real_escape_string($db, $_POST['postID']);
    $query = "DELETE FROM forum WHERE postID = $postID";

    if ($results = mysqli_query($db, $query)){
        $_SESSION['deleteSuccess'] = true;
    }
    else {
        $_SESSION['deleteSuccess'] = false;
        $_SESSION['queryPostID'] = $postID;
    }

    $_SESSION['topic'] = $_SESSION['temptopic'];
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

    //sets table up for admin/moderator mode
    if ($_SESSION['access_lvl'] > 0){
        while ($row = mysqli_fetch_assoc($results)) {

            $tempquery = "SELECT access_lvl from accounts where username = '{$row['username']}'";
            $tempresults = mysqli_query($db, $tempquery);
            $temprow = mysqli_fetch_assoc($tempresults);
            $access_lvl = $temprow['access_lvl'];


            if ($access_lvl == 2){
                $topicforum = $topicforum."<tr><td style='text-align:left; color: red'>{$row['username']} - <I>ADMIN</I></td>
                                                                <td style='text-align:left'>{$row['message']}</td>
                                                                <td style='text-align:left'>{$row['post_time']}</td>
                                                                <td style='text-align:left'>{$row['postID']}</td></tr>";
            }
            else if ($access_lvl == 1){
                $topicforum = $topicforum."<tr><td style='text-align:left; color: orange'>{$row['username']} - <I>MODERATOR</I></td>
                                                                    <td style='text-align:left'>{$row['message']}</td>
                                                                    <td style='text-align:left'>{$row['post_time']}</td>
                                                                    <td style='text-align:left'>{$row['postID']}</td></tr>";
            }
        }

    }
    // goes through the query row by row and concatanates with itself to make the table
    else {
        while ($row = mysqli_fetch_assoc($results)) {

            $tempquery = "SELECT access_lvl from accounts where username = '{$row['username']}'";
            $tempresults = mysqli_query($db, $tempquery);
            $temprow = mysqli_fetch_assoc($tempresults);
            $access_lvl = $temprow['access_lvl'];

            if ($access_lvl == 2){
                $topicforum = $topicforum."<tr><td style='text-align:left; color: red'>{$row['username']} - <I>ADMIN</I></td>
                                           <td style='text-align:left'>{$row['message']}</td>
                                           <td style='text-align:left'>{$row['post_time']}</td></tr>";
            }
            else if ($access_lvl == 1){
                $topicforum = $topicforum."<tr><td style='text-align:left; color: orange'>{$row['username']} - <I>MODERATOR</I></td>
                                           <td style='text-align:left'>{$row['message']}</td>
                                           <td style='text-align:left'>{$row['post_time']}</td></tr>";
            }
            else {
                $topicforum = $topicforum."<tr><td style='text-align:left'>{$row['username']}</td>
                                           <td style='text-align:left'>{$row['message']}</td>
                                           <td style='text-align:left'>{$row['post_time']}</td></tr>";
            }
                  
        }
    }

    $_SESSION['topicforum'] = $topicforum;
}

if (isset($_POST['reply_topic1'])) {
        header('location: reply_topic.php?topic='.$_SESSION['temptopic']);
}

if (isset($_POST['changeAccount'])) {
    $changeAccount = $_POST['accountAction'];

    switch ($changeAccount){
        case "suspendAccount":
            $_SESSION['suspendSuccess'] = false;
            $accountUsername = mysqli_real_escape_string($db, $_POST['accountUsername']);
            $query = "UPDATE accounts SET suspended = true where username ="."'$accountUsername'";
            if ($results = mysqli_query($db, $query)){
                $_SESSION['suspendSuccess'] = true;
                $_SESSION['refreshAccounts'] = true;
            }
        break;
        case "enableAccount":
            $_SESSION['enableSuccess'] = false;
            $accountUsername = mysqli_real_escape_string($db, $_POST['accountUsername']);
            $query = "UPDATE accounts SET suspended = false where username ="."'$accountUsername'";
            if ($results = mysqli_query($db, $query)){
                $_SESSION['enableSuccess'] = true;
                $_SESSION['refreshAccounts'] = true;
            }
        break;
        case "moderatorAccount":
            $_SESSION['moderatorSuccess'] = false;
            $accountUsername = mysqli_real_escape_string($db, $_POST['accountUsername']);
            $query = "UPDATE accounts SET access_lvl = 1 where username ="."'$accountUsername'";
            if ($results = mysqli_query($db, $query)){
                $_SESSION['moderatorSuccess'] = true;
                $_SESSION['refreshAccounts'] = true;
            }
        break;
            case "userAccount":
            $_SESSION['userSuccess'] = false;
            $accountUsername = mysqli_real_escape_string($db, $_POST['accountUsername']);
            $query = "UPDATE accounts SET access_lvl = 0 where username ="."'$accountUsername'";
            if ($results = mysqli_query($db, $query)){
                $_SESSION['userSuccess'] = true;
                $_SESSION['refreshAccounts'] = true;
            }
        break;
        case "deleteAccount":
            $_SESSION['deleteAccount'] = false;
            $accountUsername = mysqli_real_escape_string($db, $_POST['accountUsername']);
            $query = "DELETE FROM accounts where username ="."'$accountUsername'";
            if ($results = mysqli_query($db, $query)){
                $_SESSION['deleteAccount'] = true;
                $_SESSION['refreshAccounts'] = true;
            }
        break;
        default: die("Invalid entry");
    }
}

/* TODO: remove after more testing of radio button account admin functions using the switch statement above
if (isset($_POST['suspendAccount'])) {
    $_SESSION['suspendSuccess'] = false;
    $accountUsername = mysqli_real_escape_string($db, $_POST['accountUsername']);
    $query = "UPDATE accounts SET suspended = true where username ="."'$accountUsername'";
    if ($results = mysqli_query($db, $query)){
        $_SESSION['suspendSuccess'] = true;
        $_SESSION['refreshAccounts'] = true;
    }
}

if (isset($_POST['enableAccount'])) {
    $_SESSION['enableSuccess'] = false;
    $accountUsername = mysqli_real_escape_string($db, $_POST['accountUsername']);
    $query = "UPDATE accounts SET suspended = false where username ="."'$accountUsername'";
    if ($results = mysqli_query($db, $query)){
        $_SESSION['enableSuccess'] = true;
        $_SESSION['refreshAccounts'] = true;
    }
}

if (isset($_POST['moderatorAccount'])) {
    $_SESSION['moderatorSuccess'] = false;
    $accountUsername = mysqli_real_escape_string($db, $_POST['accountUsername']);
    $query = "UPDATE accounts SET access_lvl = 1 where username ="."'$accountUsername'";
    if ($results = mysqli_query($db, $query)){
        $_SESSION['moderatorSuccess'] = true;
        $_SESSION['refreshAccounts'] = true;
    }
}

if (isset($_POST['userAccount'])) {
    $_SESSION['userSuccess'] = false;
    $accountUsername = mysqli_real_escape_string($db, $_POST['accountUsername']);
    $query = "UPDATE accounts SET access_lvl = 0 where username ="."'$accountUsername'";
    if ($results = mysqli_query($db, $query)){
        $_SESSION['userSuccess'] = true;
        $_SESSION['refreshAccounts'] = true;
    }
}

if (isset($_POST['deleteAccount'])) {
    $_SESSION['deleteAccount'] = false;
    $accountUsername = mysqli_real_escape_string($db, $_POST['accountUsername']);
    $query = "DELETE FROM accounts where username ="."'$accountUsername'";
    if ($results = mysqli_query($db, $query)){
        $_SESSION['deleteAccount'] = true;
        $_SESSION['refreshAccounts'] = true;
    }
}
*/

if (isset($_POST['admin_accounts']) or isset($_SESSION['refreshAccounts'])) {
    $accountsList = "";
    $query = "SELECT * from accounts where access_lvl != 2 order by access_lvl, username"; //select all besides administrator
    $results = mysqli_query($db, $query);
  
    while ($row = mysqli_fetch_assoc($results)) {
        $accountsList = $accountsList."<tr><td>{$row['username']}</td>
                                           <td>{$row['fname']}</td>
                                           <td>{$row['lname']}</td>
                                           <td>{$row['email']}</td>
                                           <td>{$row['datejoined']}</td>
                                           <td>";
                                           
                                        if($row['suspended'] == 0){
                                            $accountsList = $accountsList."</td>";
                                        }
                                        else {
                                            $accountsList = $accountsList."YES</td>";
                                        }
                                        if($row['access_lvl'] == 0){
                                            $accountsList = $accountsList."<td>USER</td></tr>"; 
                                        }
                                        else if ($row['access_lvl'] == 1){
                                            $accountsList = $accountsList."<td>MODERATOR</td></tr>";
                                        }
        }
        $_SESSION['accountsList'] = $accountsList;
        unset($_POST['admin_accounts']);
        unset($_SESSION['refreshAccounts']);
}

if (isset($_POST['searchForum']) or isset($_SESSION['searchRefresh'])) {
    $searchResults = "";
    
    if (isset($_POST['searchBy'])) {
    $searchBy = $_POST['searchBy'];
    $_SESSION['searchByTerm'] = $searchTerm;
    $_SESSION['searchByRB'] = $searchBy;
    $searchTerm = mysqli_real_escape_string($db, $_POST['searchTerm']);
    }
    else {
        $searchBy = $_SESSION['searchByRB'];
        $searchTerm = $_SESSION['searchByTerm'];
        unset($_SESSION['searchRefresh']);
    }   

    if ($_SESSION['access_lvl'] > 0){
    switch ($searchBy){
        case "username": 
            $_SESSION['searchCriteria'] = " Username: ".$searchTerm;
            $query = "SELECT username, title, message, post_time, postID from forum where username like '%$searchTerm%' order by username, post_time desc";
            $results = mysqli_query($db, $query);

            while ($row = mysqli_fetch_assoc($results)) {

                $tempquery = "SELECT access_lvl from accounts where username = '{$row['username']}'";
                $tempresults = mysqli_query($db, $tempquery);
                $temprow = mysqli_fetch_assoc($tempresults);
                $access_lvl = $temprow['access_lvl'];
    
                if ($access_lvl == 2){
                    $searchResults = $searchResults."<tr><td style='text-align:left; color: red'>{$row['username']} - <I>ADMIN</I></td>
                                                <td style='align:left'>{$row['title']}</td>
                                                <td style='align:left'>{$row['message']}</td>
                                                <td style='align:left'>{$row['post_time']}</td>
                                                <td style='align:left'>{$row['postID']}</td>
                                                </tr>";
                }
                else if ($access_lvl == 1){
                    $searchResults = $searchResults."<tr><td style='text-align:left; color: orange'>{$row['username']} - <I>MODERATOR</I></td>
                                                <td style='align:left'>{$row['title']}</td>
                                                <td style='align:left'>{$row['message']}</td>
                                                <td style='align:left'>{$row['post_time']}</td>
                                                <td style='align:left'>{$row['postID']}</td>
                                                </tr>";
                }
                else {
                    $searchResults = $searchResults."<tr><td style='text-align:left;>{$row['username']}</td>
                                                <td style='align:left'>{$row['title']}</td>
                                                <td style='align:left'>{$row['message']}</td>
                                                <td style='align:left'>{$row['post_time']}</td>
                                                <td style='align:left'>{$row['postID']}</td>
                                                </tr>";
                }
            }

            $_SESSION['searchResults'] = $searchResults;

        break;
        case "topic":
            $_SESSION['searchCriteria'] = " Topic: ".$searchTerm;
            $query = "SELECT username, title, message, post_time, postID from forum where title like '%$searchTerm%' order by title, post_time desc";
            $results = mysqli_query($db, $query);

            while ($row = mysqli_fetch_assoc($results)) {
                $tempquery = "SELECT access_lvl from accounts where username = '{$row['username']}'";
                $tempresults = mysqli_query($db, $tempquery);
                $temprow = mysqli_fetch_assoc($tempresults);
                $access_lvl = $temprow['access_lvl'];
    
                if ($access_lvl == 2){
                    $searchResults = $searchResults."<tr><td style='text-align:left; color: red'>{$row['username']} - <I>ADMIN</I></td>
                                                <td style='align:left'>{$row['title']}</td>
                                                <td style='align:left'>{$row['message']}</td>
                                                <td style='align:left'>{$row['post_time']}</td>
                                                <td style='align:left'>{$row['postID']}</td>
                                                </tr>";
                }
                else if ($access_lvl == 1){
                    $searchResults = $searchResults."<tr><td style='text-align:left; color: orange'>{$row['username']} - <I>MODERATOR</I></td>
                                                <td style='align:left'>{$row['title']}</td>
                                                <td style='align:left'>{$row['message']}</td>
                                                <td style='align:left'>{$row['post_time']}</td>
                                                <td style='align:left'>{$row['postID']}</td>
                                                </tr>";
                }
                else {
                    $searchResults = $searchResults."<tr><td style='text-align:left;>{$row['username']}</td>
                                                <td style='align:left'>{$row['title']}</td>
                                                <td style='align:left'>{$row['message']}</td>
                                                <td style='align:left'>{$row['post_time']}</td>
                                                <td style='align:left'>{$row['postID']}</td>
                                                </tr>";
                }
            }

            $_SESSION['searchResults'] = $searchResults;

        break;
        case "message":
            $_SESSION['searchCriteria'] = " Message: ".$searchTerm;
            $query = "SELECT username, title, message, post_time, postID from forum where message like '%$searchTerm%' order by post_time desc";
            $results = mysqli_query($db, $query);

            while ($row = mysqli_fetch_assoc($results)) {
                $tempquery = "SELECT access_lvl from accounts where username = '{$row['username']}'";
                $tempresults = mysqli_query($db, $tempquery);
                $temprow = mysqli_fetch_assoc($tempresults);
                $access_lvl = $temprow['access_lvl'];
    
                if ($access_lvl == 2){
                    $searchResults = $searchResults."<tr><td style='text-align:left; color: red'>{$row['username']} - <I>ADMIN</I></td>
                                                <td style='align:left'>{$row['title']}</td>
                                                <td style='align:left'>{$row['message']}</td>
                                                <td style='align:left'>{$row['post_time']}</td>
                                                <td style='align:left'>{$row['postID']}</td>
                                                </tr>";
                }
                else if ($access_lvl == 1){
                    $searchResults = $searchResults."<tr><td style='text-align:left; color: orange'>{$row['username']} - <I>MODERATOR</I></td>
                                                <td style='align:left'>{$row['title']}</td>
                                                <td style='align:left'>{$row['message']}</td>
                                                <td style='align:left'>{$row['post_time']}</td>
                                                <td style='align:left'>{$row['postID']}</td>
                                                </tr>";
                }
                else {
                    $searchResults = $searchResults."<tr><td style='text-align:left;>{$row['username']}</td>
                                                <td style='align:left'>{$row['title']}</td>
                                                <td style='align:left'>{$row['message']}</td>
                                                <td style='align:left'>{$row['post_time']}</td>
                                                <td style='align:left'>{$row['postID']}</td>
                                                </tr>";
                }
            }

            $_SESSION['searchResults'] = $searchResults;

        break;
        }
    }
    else {
        switch ($searchBy){
            case "username":
                $_SESSION['searchCriteria'] = " Username: ".$searchTerm;
                $query = "SELECT username, title, message, post_time from forum where username like '%$searchTerm%' order by username, post_time desc";
                $results = mysqli_query($db, $query);
    
                while ($row = mysqli_fetch_assoc($results)) {
    
                    $tempquery = "SELECT access_lvl from accounts where username = '{$row['username']}'";
                    $tempresults = mysqli_query($db, $tempquery);
                    $temprow = mysqli_fetch_assoc($tempresults);
                    $access_lvl = $temprow['access_lvl'];
        
                    if ($access_lvl == 2){
                        $searchResults = $searchResults."<tr><td style='text-align:left; color: red'>{$row['username']} - <I>ADMIN</I></td>
                                                    <td style='align:left'>{$row['title']}</td>
                                                    <td style='align:left'>{$row['message']}</td>
                                                    <td style='align:left'>{$row['post_time']}</td>
                                                    </tr>";
                    }
                    else if ($access_lvl == 1){
                        $searchResults = $searchResults."<tr><td style='text-align:left; color: orange'>{$row['username']} - <I>MODERATOR</I></td>
                                                    <td style='align:left'>{$row['title']}</td>
                                                    <td style='align:left'>{$row['message']}</td>
                                                    <td style='align:left'>{$row['post_time']}</td>
                                                    </tr>";
                    }
                    else {
                        $searchResults = $searchResults."<tr><td style='text-align:left;>{$row['username']}</td>
                                                    <td style='align:left'>{$row['title']}</td>
                                                    <td style='align:left'>{$row['message']}</td>
                                                    <td style='align:left'>{$row['post_time']}</td>
                                                    </tr>";
                    }
                }
    
                $_SESSION['searchResults'] = $searchResults;
    
            break;
            case "topic":
                $_SESSION['searchCriteria'] = " Topic: ".$searchTerm;
                $query = "SELECT username, title, message, post_time from forum where title like '%$searchTerm%' order by title, post_time desc";
                $results = mysqli_query($db, $query);
    
                while ($row = mysqli_fetch_assoc($results)) {
                    $tempquery = "SELECT access_lvl from accounts where username = '{$row['username']}'";
                    $tempresults = mysqli_query($db, $tempquery);
                    $temprow = mysqli_fetch_assoc($tempresults);
                    $access_lvl = $temprow['access_lvl'];
        
                    if ($access_lvl == 2){
                        $searchResults = $searchResults."<tr><td style='text-align:left; color: red'>{$row['username']} - <I>ADMIN</I></td>
                                                    <td style='align:left'>{$row['title']}</td>
                                                    <td style='align:left'>{$row['message']}</td>
                                                    <td style='align:left'>{$row['post_time']}</td>
                                                    </tr>";
                    }
                    else if ($access_lvl == 1){
                        $searchResults = $searchResults."<tr><td style='text-align:left; color: orange'>{$row['username']} - <I>MODERATOR</I></td>
                                                    <td style='align:left'>{$row['title']}</td>
                                                    <td style='align:left'>{$row['message']}</td>
                                                    <td style='align:left'>{$row['post_time']}</td>
                                                    </tr>";
                    }
                    else {
                        $searchResults = $searchResults."<tr><td style='text-align:left;>{$row['username']}</td>
                                                    <td style='align:left'>{$row['title']}</td>
                                                    <td style='align:left'>{$row['message']}</td>
                                                    <td style='align:left'>{$row['post_time']}</td>
                                                    </tr>";
                    }
                }
    
                $_SESSION['searchResults'] = $searchResults;
    
            break;
            case "message":
                $_SESSION['searchCriteria'] = " Message: ".$searchTerm;
                $query = "SELECT username, title, message, post_time from forum where message like '%$searchTerm%' order by post_time desc";
                $results = mysqli_query($db, $query);
    
                while ($row = mysqli_fetch_assoc($results)) {
                    $tempquery = "SELECT access_lvl from accounts where username = '{$row['username']}'";
                    $tempresults = mysqli_query($db, $tempquery);
                    $temprow = mysqli_fetch_assoc($tempresults);
                    $access_lvl = $temprow['access_lvl'];
        
                    if ($access_lvl == 2){
                        $searchResults = $searchResults."<tr><td style='text-align:left; color: red'>{$row['username']} - <I>ADMIN</I></td>
                                                    <td style='align:left'>{$row['title']}</td>
                                                    <td style='align:left'>{$row['message']}</td>
                                                    <td style='align:left'>{$row['post_time']}</td>
                                                    </tr>";
                    }
                    else if ($access_lvl == 1){
                        $searchResults = $searchResults."<tr><td style='text-align:left; color: orange'>{$row['username']} - <I>MODERATOR</I></td>
                                                    <td style='align:left'>{$row['title']}</td>
                                                    <td style='align:left'>{$row['message']}</td>
                                                    <td style='align:left'>{$row['post_time']}</td>
                                                    </tr>";
                    }
                    else {
                        $searchResults = $searchResults."<tr><td style='text-align:left;>{$row['username']}</td>
                                                    <td style='align:left'>{$row['title']}</td>
                                                    <td style='align:left'>{$row['message']}</td>
                                                    <td style='align:left'>{$row['post_time']}</td>
                                                    </tr>";
                    }
                }
    
                $_SESSION['searchResults'] = $searchResults;
    
            break;
        }
    }
    header('location: search_results.php');
}
?>