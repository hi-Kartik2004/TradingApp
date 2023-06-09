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
    global $conn;
    $email = $_SESSION["login"]["data"]["email"];
    $setLoginQuery = "UPDATE `users` SET `connect` = '0' WHERE `users`.`email` = '$email';";
    $run2 = mysqli_query($conn, $setLoginQuery);
    if ($run2) {
        // Handling the logout for details_table

        // Select the maximum `updated_at` value for the given email
        $subQuery = "SELECT MAX(`updated_at`) FROM `user_details` WHERE `email` = '$email'";
        $subResult = mysqli_query($conn, $subQuery);

        if ($subResult && mysqli_num_rows($subResult) > 0) {
            // Fetch the maximum updated_at value
            $row = mysqli_fetch_assoc($subResult);
            $maxUpdatedAt = $row['MAX(`updated_at`)'];

            // Update the status column to 0 for the rows that match the email and have the maximum updated_at value
            $updateQuery = "UPDATE `user_details` SET `status` = 0 WHERE `email` = '$email';";
            $updateResult = mysqli_query($conn, $updateQuery);

            if ($updateResult) {
                // echo "Account status updated successfully for the most recent record.";
                // echo "inside";
                unset($_SESSION);
                session_destroy();
                header("location: ../../?home");
            } else {
                $_SESSION["err"]["err_msg"] = "Could not update Login History!";
                unset($_SESSION);
                session_destroy();
                header("location: ../../?login");
            }
        } else {
            $_SESSION["err"]["err_msg"] = "No login history found!";
            unset($_SESSION);
            session_destroy();
            header("location: ../../?login");
        }
    } else {
        $_SESSION["err"] = array();
        $_SESSION["err"]["err_msg"] = "Unable to connect with the database!";
        // echo "Unable to connect with the database";
        header("location: ../?profile");
    }
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
        $ip = $_SERVER['REMOTE_ADDR'];
        $query = "INSERT INTO `users` (`first_name`, `last_name`, `email`, `password`,`auth`, `connect`, `ip`) VALUES ('$firstName', '$lastName', '$email', '$password', 1, 'v', '$ip' );";
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
            $_SESSION["err"]["err_msg"] = "Verfication Failed! Something went wrong!";
            header("location: ../../?login");
        }
    } else {
        $_SESSION["err"] = array();
        $_SESSION["err"]["err_msg"] = "Recheck if you are using the same browser which was used to register!, else Code mismtach!";
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
                $ip = $ipAddress = $_SERVER['REMOTE_ADDR'];
                $setLoginQuery = "UPDATE `users` SET `connect` = '1', `ip` = '$ip' WHERE `users`.`email` = '$email';";
                $run2 = mysqli_query($conn, $setLoginQuery);
                if ($run2) {
                    $_SESSION["err"] = array();
                    $_SESSION["err"]["err_msg"] = "Login Success!";
                    $_SESSION["login"]["status"] = 1;
                    $_SESSION["login"]["data"] = $data;
                    global $conn;
                    $setLoginHistoryQuery = "INSERT INTO `user_details` (`email`, `updated_at`, `ip`, `status`) VALUES ('$email', current_timestamp(), '$ip', '1');";
                    $runDetails = mysqli_query($conn, $setLoginHistoryQuery);
                    if ($runDetails) {
                    }
                    if (!$runDetails) {
                        $_SESSION["err"]["err_msg"] = "Could not update Login History!";
                    }

                    // Accessing the user_details for login history;
                    $selectQuery = "SELECT * FROM `user_details` WHERE `email` = '$email';";
                    $result = mysqli_query($conn, $selectQuery);
                    $loginHistory = mysqli_fetch_all($result);
                    $_SESSION["login"]["loginHistory"] = $loginHistory;
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

    // print_r($_POST);
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
                $selectQuery2 = "SELECT * FROM `users` WHERE email = '$email';";
                $run2 = mysqli_query($conn, $selectQuery2);
                $data = mysqli_fetch_assoc($run2);
                if (mysqli_num_rows($run2) > 0) {
                    $_SESSION["login"]["status"] = 1;
                    $_SESSION["login"]["data"] = $data;
                    header("location: ../?profile");
                } else {
                    $_SESSION["err"]["err_msg"] = "Something went wrong!";
                    header("location: ../?profile");
                }
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


// ============== handling add to cart =============
if (isset($_GET["add"])) {
    $identifier = $_GET["add"];
    if (isset($_GET["price"]) && isset($_GET["symbol"])) {
        $price = $_GET["price"];
        $symbol = $_GET["symbol"];
        global $conn;
        $email = $_SESSION["login"]["data"]["email"];
        $selectQuery = "SELECT * FROM `cart` WHERE `email` = '$email' AND `identifier` = '$identifier';";
        $runSelectQuery = mysqli_query($conn, $selectQuery);
        if (mysqli_num_rows($runSelectQuery) > 0) {
            $_SESSION["err"]["err_msg"] = "The item is already in the cart, Increase Quantity";
            header("location: ../?cart");
        } else {
            $insertIntoCartQuery = "INSERT INTO `cart` (`email`, `identifier`, `symbol`, `price`) VALUES ('$email', '$identifier', '$symbol', '$price');";
            $run = mysqli_query($conn, $insertIntoCartQuery);
            if ($run) {
                $_SESSION["err"]["err_msg"] = "Stock Added to cart Successfully";
            } else {
                $_SESSION["err"]["err_msg"] = "Unable to add to cart!";
            }
            header("location: ../?buy");
        }
    } else {
        $_SESSION["err"]["err_msg"] = "Some Value was not passed to the cart!";
        header("location: ../?cart");
    }
}


//  ============== Handling delete item =============
if (isset($_GET["delete-item"])) {
    global $conn;
    $email = $_SESSION["login"]["data"]["email"];
    if ($_GET["delete-item"] == "all") {
        $deleteQuery = "DELETE FROM `cart` WHERE `email` = '$email'";
        $delete = mysqli_query($conn, $deleteQuery);
        if ($delete) {
            $_SESSION["err"]["err_msg"]["payment"] = "Cleared cart!";
            header("location: ../?cart");
        } else {
            $_SESSION["err"]["err_msg"]["payment"] = "Unable to clear cart!";
        }
    } else {
        $_SESSION["err"]["err_msg"]["payment"] = "delete-item accessed!";
        header("location: ../");
    }
}

// =============== Handling make payment / Purchase ===============
// if (isset($_GET["make-payment"])) {
//     global $conn;
//     $email = $_SESSION["login"]["data"]["email"];
//     $amount = $_GET["make-payment"];
//     if ($amount > 0) {

//         $deleteQuery = "DELETE FROM `cart` WHERE `email` = '$email'";
//         $delete = mysqli_query($conn, $deleteQuery);
//         if ($delete) {
//             $fetchCurrentBalanceQuery = "SELECT `balance` FROM `users` WHERE `email` = '$email';";
//             $fetchCurrentBalance = mysqli_query($conn, $fetchCurrentBalanceQuery);
//             if ($fetchCurrentBalance) {
//                 $currentBalance = mysqli_fetch_assoc($fetchCurrentBalance);
//                 $balance = $currentBalance["balance"] - $amount;

//                 // Assuming you have already established a database connection
//                 if (isset($_GET['make-payment']) && isset($_GET['identifier1']) && isset($_GET['quantity1'])) {
//                     $totalAmount = $_GET['make-payment'];
//                     $paymentTable = "transactions"; // Replace with your actual table name
//                     // Insert the total amount into the payments table
//                     $insertPaymentQuery = "INSERT INTO $paymentTable (transaction_amt, email) VALUES ('$amount', '$email')";
//                     $insertPaymentResult = mysqli_query($conn, $insertPaymentQuery);

//                     if ($insertPaymentResult) {
//                         // Get the ID of the inserted payment
//                         $paymentId = mysqli_insert_id($conn);

//                         // Loop through the GET parameters and insert them into the table
//                         for ($i = 1; isset($_GET['identifier' . $i]) && isset($_GET['quantity' . $i]); $i++) {
//                             $identifier = $_GET['identifier' . $i];
//                             $quantity = $_GET['quantity' . $i];

//                             // Insert the identifier, quantity, and payment ID into the table
//                             $insertDataQuery = "INSERT INTO $paymentTable (identifier, quantity, payment_id) VALUES ('$identifier', '$quantity', '$paymentId')";
//                             $insertDataResult = mysqli_query($conn, $insertDataQuery);

//                             if (!$insertDataResult) {
//                                 // Handle insertion error if needed
//                                 echo "Failed to insert data for identifier: $identifier and quantity: $quantity";
//                             }
//                         }
//                     } else {
//                         // Handle insertion error if needed
//                         $_SESSION["err"]["err_msg"] =  "Failed to insert payment data.";
//                         header("location: ../?history");
//                         exit();
//                     }
//                 } else {
//                     // Handle missing parameters error if needed
//                     $_SESSION["err"]["err_msg"] =  "Missing payment parameters.";
//                     header("location: ../?history");
//                     exit();
//                 }

//                 if ($balance < 0) {
//                     $_SESSION["err"]["err_msg"] = "Insufficient Balance! - Updation Failed!";

//                     // Get the ID of the most recent transaction for the email
//                     $recentTransactionQuery = "SELECT MAX(`id`) AS recent_transaction_id FROM `transactions` WHERE `email` = '$email'";
//                     $recentTransactionResult = mysqli_query($conn, $recentTransactionQuery);

//                     if ($recentTransactionResult && mysqli_num_rows($recentTransactionResult) > 0) {
//                         $recentTransactionRow = mysqli_fetch_assoc($recentTransactionResult);
//                         $recentTransactionId = $recentTransactionRow["recent_transaction_id"];

//                         // Update the transaction with the recent ID to set status to '0'
//                         $updateTransactionStatusQuery = "UPDATE `transactions` SET `status` = '0' WHERE `id` = '$recentTransactionId'";
//                         $updateTransactionStatusResult = mysqli_query($conn, $updateTransactionStatusQuery);

//                         if ($updateTransactionStatusResult) {
//                             $rowsAffected = mysqli_affected_rows($conn);

//                             if ($rowsAffected > 0) {
//                                 $_SESSION["err"]["err_msg"] = "Insufficient Balance! - Transaction Failed! ";
//                                 header("location: ../?history");
//                                 exit();
//                             } else {
//                                 $_SESSION["err"]["err_msg"] = "Unable to set transaction status ";
//                                 header("location: ../?history");
//                             }
//                         } else {
//                             $_SESSION["err"]["err_msg"] = "Failed to update transaction status";
//                             header("location: ../?history");
//                         }
//                     } else {
//                         $_SESSION["err"]["err_msg"] = "Failed to fetch recent transaction";
//                         header("location: ../?history");
//                     }

//                     exit();
//                 }

//                 $_SESSION["login"]["data"]["balance"] = $balance;
//                 $updateBalanceQuery = "UPDATE `users` SET `balance` = '$balance' WHERE `users`.`email` = '$email';";
//                 $updateBalance = mysqli_query($conn, $updateBalanceQuery);
//                 if ($updateBalance) {
//                     $_SESSION["err"]["err_msg"]["payment"] = "Transaction Success of Rs" . $amount;
//                     header("location: ../?history");
//                     exit();
//                 } else {
//                     $_SESSION["err"]["err_msg"]["payment"] = "Failed to update Balance: " . mysqli_error($conn);
//                     header("location: ../?history");
//                     exit();
//                 }
//             } else {
//                 $_SESSION["err"]["err_msg"]["payment"] = "Failed to fetch current Balance: " . mysqli_error($conn);
//                 header("location: ../?history");
//                 exit();
//             }
//         } else {
//             $_SESSION["err"]["err_msg"]["payment"] = "Transaction Success of Rs" . $amount . "But failed to update Cart: " . mysqli_error($conn);
//             header("location: ../?history");
//             exit();
//         }
//     } else {
//         $_SESSION["err"]["err_msg"]["payment"] = "Amount is Rs 0, Transaction Failed";
//         header("location: ../?cart");
//         exit();
//     }

// if ($amount > 0) {
//     $insertQuery = "INSERT INTO `transactions` (`email`, `transaction_amt`, `status`) VALUES ('$email', '$amount', 1);";
//     $run = mysqli_query($conn, $insertQuery);
//     if ($run) {
//         $deleteQuery = "DELETE FROM `cart` WHERE `email` = '$email'";
//         $delete = mysqli_query($conn, $deleteQuery);
//         if ($delete) {
//             $fetchCurrentBalanceQuery = "SELECT `balance` FROM `users` WHERE `email` = '$email';";
//             $fetchCurrentBalance = mysqli_query($conn, $fetchCurrentBalanceQuery);
//             if ($fetchCurrentBalance) {
//                 $currentBalance = mysqli_fetch_assoc($fetchCurrentBalance);
//                 $balance = $currentBalance["balance"] - $amount;
//                 if ($balance < 0) {
//                     $_SESSION["err"]["err_msg"] = "Insufficient Balance! - Updation Failed!";

//                     // Get the ID of the most recent transaction for the email
//                     $recentTransactionQuery = "SELECT MAX(`id`) AS recent_transaction_id FROM `transactions` WHERE `email` = '$email'";
//                     $recentTransactionResult = mysqli_query($conn, $recentTransactionQuery);

//                     if ($recentTransactionResult && mysqli_num_rows($recentTransactionResult) > 0) {
//                         $recentTransactionRow = mysqli_fetch_assoc($recentTransactionResult);
//                         $recentTransactionId = $recentTransactionRow["recent_transaction_id"];

//                         // Update the transaction with the recent ID to set status to '0'
//                         $updateTransactionStatusQuery = "UPDATE `transactions` SET `status` = '0' WHERE `id` = '$recentTransactionId'";
//                         $updateTransactionStatusResult = mysqli_query($conn, $updateTransactionStatusQuery);

//                         if ($updateTransactionStatusResult) {
//                             $rowsAffected = mysqli_affected_rows($conn);

//                             if ($rowsAffected > 0) {
//                                 $_SESSION["err"]["err_msg"] = "Insufficient Balance! - Transaction Failed! ";
//                                 header("location: ../?history");
//                                 exit();
//                             } else {
//                                 $_SESSION["err"]["err_msg"] = "Unable to set transaction status ";
//                                 header("location: ../?history");
//                             }
//                         } else {
//                             $_SESSION["err"]["err_msg"] = "Failed to update transaction status";
//                             header("location: ../?history");
//                         }
//                     } else {
//                         $_SESSION["err"]["err_msg"] = "Failed to fetch recent transaction";
//                         header("location: ../?history");
//                     }

//                     exit();
//                 }



//                 $_SESSION["login"]["data"]["balance"] = $balance;
//                 $updateBalanceQuery = "UPDATE `users` SET `balance` = '$balance' WHERE `users`.`email` = '$email';";
//                 $updateBalance = mysqli_query($conn, $updateBalanceQuery);
//                 if ($updateBalance) {
//                     $_SESSION["err"]["err_msg"]["payment"] = "Transaction Success of Rs" . $amount;
//                     header("location: ../?history");
//                     exit();
//                 } else {
//                     $_SESSION["err"]["err_msg"]["payment"] = "Failed to update Balance: " . mysqli_error($conn);
//                     header("location: ../?history");
//                     exit();
//                 }
//             } else {
//                 $_SESSION["err"]["err_msg"]["payment"] = "Failed to fetch current Balance: " . mysqli_error($conn);
//                 header("location: ../?history");
//                 exit();
//             }
//         } else {
//             $_SESSION["err"]["err_msg"]["payment"] = "Transaction Success of Rs" . $amount . "But failed to update Cart: " . mysqli_error($conn);
//             header("location: ../?history");
//             exit();
//         }
//     } else {
//         $_SESSION["err"]["err_msg"]["payment"] = "Could not insert the amount in the database - transaction failed: " . $amount;
//         header("location: ../?cart");
//         exit();
//     }
// } else {
//     $_SESSION["err"]["err_msg"]["payment"] = "Amount is Rs 0, Transaction Failed";
//     header("location: ../?cart");
//     exit();
// }
// }




// require_once("php/config.php");
// $conn = mysqli_connect(server, host, password, db_name);
// $email = $_SESSION["login"]["data"]["email"];
// $selectQuery = "SELECT * FROM `cart` WHERE `email` = '$email'";
// $run = mysqli_query($conn, $selectQuery);
// if ($run) {
//     if (mysqli_num_rows($run) > 0) {
//         $cartData = mysqli_fetch_all($run, MYSQLI_ASSOC);
//     } else {
//         $_SESSION["err"]["err_msg"] = "Your cart is Empty";
//     }
// } else {
//     $_SESSION["err"]["err_msg"] = "Not able to connect with Database - from cart";
// }
if (isset($_GET["make-payment"])) {
    global $conn;
    $email = $_SESSION["login"]["data"]["email"];
    $totalAmount = $_GET["make-payment"];

    if ($totalAmount > 0) {
        // Begin transaction
        mysqli_begin_transaction($conn);

        try {
            // Insert the payment details into the transactions table
            $insertPaymentQuery = "INSERT INTO `transactions` (`email`, `transaction_amt`, `status`) VALUES ('$email', '$totalAmount', 1)";
            mysqli_query($conn, $insertPaymentQuery);

            $paymentId = mysqli_insert_id($conn); // Get the ID of the inserted payment

            // Loop through the GET parameters to insert card details
            $i = 1;
            while (isset($_GET["identifier$i"]) && isset($_GET["quantity$i"]) && isset($_GET["amount$i"])) {
                $identifier = $_GET["identifier$i"];
                $quantity = $_GET["quantity$i"];
                $stockPrice = $_GET["amount$i"];

                if ($quantity > 0) {
                    // Insert the card details into the transactions table
                    $insertCardDetailsQuery = "INSERT INTO `transactions` (`id`, `identifier`, `transaction_amt`, `stock_price`, `quantity`, `email`, `status`) VALUES ('$paymentId', '$identifier', '$totalAmount', '$stockPrice', '$quantity', '$email', 1)";
                    mysqli_query($conn, $insertCardDetailsQuery);
                }

                $i++;
            }

            // Update the status to '1' in the transactions table
            $updateStatusQuery = "UPDATE `transactions` SET `status` = 1 WHERE `id` = '$paymentId'";
            mysqli_query($conn, $updateStatusQuery);

            // Delete cart items
            $deleteQuery = "DELETE FROM `cart` WHERE `email` = '$email'";
            mysqli_query($conn, $deleteQuery);

            // Fetch current balance
            $fetchCurrentBalanceQuery = "SELECT `balance` FROM `users` WHERE `email` = '$email';";
            $fetchCurrentBalanceResult = mysqli_query($conn, $fetchCurrentBalanceQuery);
            $currentBalance = mysqli_fetch_assoc($fetchCurrentBalanceResult);
            $balance = $currentBalance["balance"] - $totalAmount;

            if ($balance < 0) {
                // Insufficient balance
                $_SESSION["err"]["err_msg"] = "Insufficient Balance! - Updation Failed!";
                header("location: ../?history");
                mysqli_rollback($conn); // Rollback the transaction
                exit();
            }

            // Update user's balance
            $_SESSION["login"]["data"]["balance"] = $balance;
            $updateBalanceQuery = "UPDATE `users` SET `balance` = '$balance' WHERE `email` = '$email';";
            mysqli_query($conn, $updateBalanceQuery);

            // Commit the transaction
            mysqli_commit($conn);

            $_SESSION["err"]["err_msg"]["payment"] = "Transaction Success of Rs" . $totalAmount;
            header("location: ../?history");
            exit();
        } catch (Exception $e) {
            // Handle exceptions or errors that occurred during the transaction
            mysqli_rollback($conn); // Rollback the transaction
            $_SESSION["err"]["err_msg"]["payment"] = "Transaction failed: " . $e->getMessage();
            header("location: ../?cart");
            exit();
        }
    } else {
        $_SESSION["err"]["err_msg"]["payment"] = "Amount is Rs 0, Transaction Failed";
        header("location: ../?cart");
        exit();
    }
}

// ================= Handling sell stocks =============
if (isset($_GET["sell"])) {
    global $conn;
    $email = $_SESSION["login"]["data"]["email"];
    $currentStockAmount = $_GET["sell"];
    // print_r($_GET);

    if ($currentStockAmount > 0) {
        // Begin transaction
        // mysqli_begin_transaction($conn);

        try {
            // Insert the payment details into the transactions table
            $insertPaymentQuery = "INSERT INTO `transactions` (`email`, `transaction_amt`, `status`) VALUES ('$email', '$totalAmount', -1)";
            mysqli_query($conn, $insertPaymentQuery);

            $paymentId = mysqli_insert_id($conn); // Get the ID of the inserted payment

            // Loop through the GET parameters to insert card details

            $identifier = $_GET["identifier"];
            $quantity = $_GET["quantity"];
            $stockPrice = $_GET["stockPrice"];
            $totalAmount = $currentStockAmount * $quantity;
            if ($quantity > 0) {
                // Insert the card details into the transactions table
                $insertCardDetailsQuery = "INSERT INTO `transactions` (`id`, `identifier`, `transaction_amt`, `stock_price`, `quantity`, `email`, `status`) VALUES ('$paymentId', '$identifier', '$totalAmount', '$stockPrice', '$quantity', '$email', -1)";
                mysqli_query($conn, $insertCardDetailsQuery);
            }


            // Update previous transaction...
            $updateQuery = "UPDATE transactions
                SET status = -2
                WHERE email = '$email' AND stock_price = $stockPrice AND quantity = $quantity AND status = 1
                LIMIT 1;";
            mysqli_query($conn, $updateQuery);
            if (mysqli_affected_rows($conn) > 0) {
                // Fetch current balance
                $fetchCurrentBalanceQuery = "SELECT `balance` FROM `users` WHERE `email` = '$email';";
                $fetchCurrentBalanceResult = mysqli_query($conn, $fetchCurrentBalanceQuery);
                $currentBalance = mysqli_fetch_assoc($fetchCurrentBalanceResult);
                $balance = $currentBalance["balance"] + $totalAmount;

                if ($balance < 0) {
                    // Insufficient balance
                    $_SESSION["err"]["err_msg"] = "Insufficient Balance! - Updation Failed!";
                    header("location: ../?history");
                    mysqli_rollback($conn); // Rollback the transaction
                    exit();
                }

                // Update user's balance
                $_SESSION["login"]["data"]["balance"] = $balance;
                $updateBalanceQuery = "UPDATE `users` SET `balance` = '$balance' WHERE `email` = '$email';";
                mysqli_query($conn, $updateBalanceQuery);

                // Commit the transaction
                mysqli_commit($conn);

                $_SESSION["err"]["err_msg"]["payment"] = "Transaction Success of Rs" . $totalAmount;
                header("location: ../?history");
                exit();
            }
        } catch (Exception $e) {
            // Handle exceptions or errors that occurred during the transaction
            mysqli_rollback($conn); // Rollback the transaction
            $_SESSION["err"]["err_msg"]["payment"] = "Transaction failed: " . $e->getMessage();
            header("location: ../?cart");
            exit();
        }
    } else {
        $_SESSION["err"]["err_msg"]["payment"] = "Stock Amount is Rs 0, Transaction Failed";
        header("location: ../?history");
        exit();
    }
}

// ================= Delete - item ===========
if (isset($_GET["delete"])) {
    global $conn;
    $email = $_SESSION["login"]["data"]["email"];
    $identifier = $_GET["delete"];
    $deleteQuery = "DELETE FROM `cart` WHERE `email` = '$email' AND `identifier` = '$identifier'";
    $delete = mysqli_query($conn, $deleteQuery);
    if ($delete) {
        $_SESSION["err"]["err_msg"]["payment"] = "Item deleted!";
        header("location: ../../?cart");
    } else {
        $_SESSION["err"]["err_msg"]["payment"] = "Unable to delete-item from cart!";
        header("location: ../?cart");
    }
}
