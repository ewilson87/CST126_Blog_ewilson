<?php
/**
 * CST-126 Blog project
 * server.php version 1.0
 * Program Author: Evan Wilson
 * Date: 10/9/2018
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
        $password = $password_1;

        $query = "INSERT INTO accounts (username, password, fname, lname, email, datejoined) 
  			  VALUES('$username', '$password', '$fname', '$lname', '$email', CURRENT_DATE)";
        mysqli_query($db, $query);
        $_SESSION['username'] = $username;
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

    //Currently displays username upon logging in. Might change to display first name in the future.
    //Queries SQL to verify a match for username and password
    if (count($errors) == 0) {
        $query = "SELECT * FROM accounts WHERE username='$username' AND password='$password'";
        $results = mysqli_query($db, $query);
        if (mysqli_num_rows($results) == 1) {
            $_SESSION['username'] = $username;
            $_SESSION['success'] = "You are now logged in";
            header('location: index.php');
        }else {
            array_push($errors, "Wrong username/password combination");
        }
    }
}
?>