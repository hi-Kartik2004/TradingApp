<?php
session_start();
require_once("config.php");
// This is a actions page.
echo "actions page";

//=============== Handling register ================

if (isset($_GET['register'])) {
    $firstName = $_POST["firstname"];
    $lastName = $_POST["lastname"];
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];
    $email = $_POST["email"];

    $conn = mysqli_connect(server, host, password, db_name);

    if ($conn) {
        if ($cpassword == $password) {
            $selectQuery = "SELECT * FROM `users` WHERE email = '$email';";
            $run = mysqli_query($conn, $selectQuery);
            if (mysqli_num_rows($run) > 0) {
                echo "User exists";
                $_SESSION["err"] = array();
                $_SESSION["err"]["err_msg"] = "User already exists!, Kindly Login!";
                header("location: ../?register");
            } else {
                $query = "INSERT INTO `users` (`first_name`, `last_name`, `email`, `password`, `connect`) VALUES ('$firstName', '$lastName', '$email', '$password', '');";
                $run = mysqli_query($conn, $query);

                if ($run) {
                    echo "record inserted";
                    $_SESSION["err"] = array();
                    $_SESSION["err"]["err_msg"] = "SignUp Success, Kindly Login below!";
                    header("location: ../?login");
                } else {
                    echo "Failed to insert";
                    $_SESSION["err"] = array();
                    $_SESSION["err"]["err_msg"] = "Something went wrong!, we are working on it!";
                    header("location: ../?register");
                }
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
            echo "User exists";
            if ($password == $data["password"]) {
                $setLoginQuery = "UPDATE `users` SET `connect` = '1' WHERE `users`.`email` = '$email';";
                $run2 = mysqli_query($conn, $setLoginQuery);
                if ($run2) {
                    $_SESSION["err"] = array();
                    $_SESSION["err"]["err_msg"] = "Login Success!";
                    $_SESSION["login"]["status"] = 1;
                    $_SESSION["login"]["data"] = $data;
                    header("location: ../?dashboard");
                } else {
                    $_SESSION["err"] = array();
                    $_SESSION["err"]["err_msg"] = "unable to connect with database!";
                    echo "unable to connect with database";
                    header("location: ../?login");
                }
            } else {
                $_SESSION["err"] = array();
                $_SESSION["err"]["err_msg"] = "Incorrect Password!";
                header("location: ../?login");
            }
        } else {
            echo "Failed to insert";
            $_SESSION["err"] = array();
            $_SESSION["err"]["err_msg"] = "User does not exist! Register Now";
            header("location: ../?login");
        }
    } else {
        $_SESSION["err"] = array();
        $_SESSION["err"]["err_msg"] = "unable to connect with database!";
        echo "unable to connect with database";
        header("location: ../?register");
    }
}
