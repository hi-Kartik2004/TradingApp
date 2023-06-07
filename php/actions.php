<?php
error_reporting(E_ALL);
session_start();
require_once("config.php");
require_once("sendmail.php");
$mail->SMTPDebug = 0; // Disable debug output

// This is a actions page.
// echo "actions page";
$conn = mysqli_connect(server, host, password, db_name);

// ============= Logout ============

if (isset($_GET['logout'])) {
    unset($_SESSION);
    session_destroy();
    header("location: ../../?home");
}

//=============== Handling register ================

if (isset($_GET['register'])) {
    $firstName = $_POST["firstname"];
    $lastName = $_POST["lastname"];
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];
    $email = $_POST["email"];
    $_SESSION["prev_firstname"] = $firstName;
    $_SESSION["prev_lastname"] = $lastName;
    $_SESSION["prev_password"] = $password;
    $_SESSION["prev_email"] = $email;
    // $_SESSION["prev_firstname"] = $firstName;


    $conn = mysqli_connect(server, host, password, db_name);

    if ($conn) {
        if ($cpassword == $password) {
            $selectQuery = "SELECT * FROM `users` WHERE email = '$email';";
            $run = mysqli_query($conn, $selectQuery);
            if (mysqli_num_rows($run) > 0) {
                // echo "User exists";
                $_SESSION["err"] = array();
                $_SESSION["err"]["err_msg"] = "User already exists!, Kindly Login!";
                header("location: ../?register");
            } else {
                sendVerificationMail($email);
            }
        } else {
            $_SESSION["err"] = array();
            $_SESSION["err"]["err_msg"] = "Password Mismatch";
            header("location: ../?register");
        }
    } else {
        echo "no";
    }
}

// ============ Verification =============
if (isset($_GET["verify"])) {
    $code = $_GET["verify"];
    // echo $code;
    global $conn;
    if ($code == $_SESSION["verification_code"] && isset($_SESSION["prev_email"])) {
        // echo "inside";
        $firstName = $_SESSION["prev_firstname"];
        $lastName = $_SESSION["prev_lastname"];
        $email = $_SESSION["prev_email"];
        $password = $_SESSION["prev_password"];
        $query = "INSERT INTO `users` (`first_name`, `last_name`, `email`, `password`,`auth`, `connect`) VALUES ('$firstName', '$lastName', '$email', '$password', 1, '');";
        $run = mysqli_query($conn, $query);

        if ($run) {
            // echo "record inserted";
            $_SESSION["err"] = array();
            $_SESSION["err"]["err_msg"] = "SignUp Success, Kindly Login below!";
            unset($_SESSION["verification_code"]);
            header("location: ../../?login");
        } else {
            // echo "Failed to insert";
            $_SESSION["err"] = array();
            $_SESSION["err"]["err_msg"] = "Something went wrong!, we are working on it!";
            header("location: ../../?login");
        }
    } else {
        $_SESSION["err"] = array();
        $_SESSION["err"]["err_msg"] = "Something went wrong!, we are working on it!";
        header("location: ../../?login");
    }
}

// ============== Handling login ================
if (isset($_GET['login'])) {
    $password = $_POST["password"];
    $email = $_POST["email"];

    $conn = mysqli_connect(server, host, password, db_name);

    if ($conn) {
        $selectQuery = "SELECT * FROM `users` WHERE email = '$email';";
        $run = mysqli_query($conn, $selectQuery);
        $data = mysqli_fetch_assoc($run);
        if (mysqli_num_rows($run) > 0) {
            // echo "User exists";
            if ($password == $data["password"]) {
                $setLoginQuery = "UPDATE `users` SET `connect` = '1' WHERE `users`.`email` = '$email';";
                $run2 = mysqli_query($conn, $setLoginQuery);
                if ($run2) {
                    $_SESSION["err"] = array();
                    $_SESSION["err"]["err_msg"] = "Login Success!";
                    $_SESSION["login"]["status"] = 1;
                    $_SESSION["login"]["data"] = $data;
                    header("location: ../?profile");
                } else {
                    $_SESSION["err"] = array();
                    $_SESSION["err"]["err_msg"] = "unable to connect with database!";
                    // echo "unable to connect with database";
                    header("location: ../?login");
                }
            } else {
                $_SESSION["err"] = array();
                $_SESSION["err"]["err_msg"] = "Incorrect Password!";
                header("location: ../?login");
            }
        } else {
            // echo "Failed to insert";
            $_SESSION["err"] = array();
            $_SESSION["err"]["err_msg"] = "User does not exist! Register Now";
            header("location: ../?login");
        }
    } else {
        $_SESSION["err"] = array();
        $_SESSION["err"]["err_msg"] = "unable to connect with database!";
        // echo "unable to connect with database";
        header("location: ../?register");
    }
}

// =============== Handling edit profile =============
if (isset($_GET['edit-profile'])) {
    $firstName = $_POST["firstname"];
    $lastName = $_POST["lastname"];
    $email = $_SESSION["login"]["data"]["email"];
    $phone = $_POST["phone"];

    print_r($_POST);
    // echo $email;

    if (isset($firstName) && isset($lastName) && isset($phone)) {
        // echo "inside";
        if (empty(trim($firstName))) {
            $_SESSION["err"]["err_msg"] = "First Name cannot be blank";
            header("location:../?edit-profile");
        }
        if (empty(trim($lastName))) {
            $_SESSION["err"]["err_msg"] = "Last Name cannot be blank";
            header("location:../?edit-profile");
        }
        if (empty(trim($phone))) {
            $_SESSION["err"]["err_msg"] = "Phone number cannot be blank";
            header("location:../?edit-profile");
        }

        $conn = mysqli_connect(server, host, password, db_name);

        if ($conn) {

            $selectQuery = "UPDATE `users` SET `first_name` = '$firstName', `last_name` = '$lastName', `phone` = '$phone' WHERE `users`.`email` = '$email'";

            $run = mysqli_query($conn, $selectQuery);
            if ($run) {
                // echo "User exists";
                $_SESSION["err"]["err_msg"] = "Details Updated Successfully!";
                // echo "updated";
                header("location: ../?profile");
            } else {
                // echo "failed";
                $_SESSION["err"]["err_msg"] = "Something went wrong! Updating details failed!";
                header("location: ../?profile");
            }
            // } else {
            //     $_SESSION["err"] = array();
            //     $_SESSION["err"]["err_msg"] = "Password Mismatch";
            //     header("location: ../?register");
            // }
        } else {
            echo "db not connected";
        }
    } else {
        $_SESSION["err"]["err_msg"] = "Something was left blank!";
        header("location: ../?edit-profile");
    }
}

// ================ Handling edit password ================
if (isset($_GET["edit-password"])) {
    $password = $_POST["newPassword"];
    $cpassword = $_POST["cpassword"];
    $oldPassword = $_POST["oldPassword"];
    $email = $_SESSION["login"]["data"]["email"];

    $conn = mysqli_connect(server, host, password, db_name);

    if ($conn) {
        if ($cpassword == $password) {
            $selectQuery = "UPDATE `users` SET `password` = '$password' WHERE `users`.`email` = '$email';";
            $run = mysqli_query($conn, $selectQuery);
            if ($run) {
                $_SESSION["err"]["err_msg"] = "Password Successfully Changed!";
                header("location: ../?profile");
            } else {
                $_SESSION["err"]["err_msg"] = "Something went wrong!";
                header("location: ../?profile");
            }
        } else {
            $_SESSION["err"] = array();
            $_SESSION["err"]["err_msg"] = "Password Mismatch";
            header("location: ../?edit-password");
        }
    } else {
        echo "db not connected!";
    }
}



// ============== Handling forgot Password =========
if (isset($_GET["forgot"])) {
    $conn = mysqli_connect(server, host, password, db_name);
    $email = $_POST["email"];
    $selectQuery = "SELECT * FROM `users` WHERE email = '$email';";
    $run = mysqli_query($conn, $selectQuery);
    if ($run) {
        if (mysqli_num_rows($run) > 0) {
            $data = mysqli_fetch_assoc($run);
            $password = $data["password"];
            sendForgotMail($email, $password);
        } else {
            $_SESSION["err"]["err_msg"] = "User does not exist!";
            header("location: ../?forgot");
        }
    } else {
        $_SESSION["err"]["err_msg"] = "Unable to send Mails!";
        header("location: ../?forgot");
    }
}
