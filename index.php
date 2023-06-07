<?php
// starting session for handling errors;

session_start();

// Handling verfication of email
if (isset($_GET["verify"])) {
    $code = $_GET["verify"];
    header("location: php/actions.php/?verify=" . $code);
}

// Handling logout
if (isset($_GET["logout"])) {
    header("location: php/actions.php/?logout=1");
}

// This the head of the html
include("pages/components/head.php");

// All the components go below.
include("pages/components/navbar.php");


// Handling user login routers
if (isset($_SESSION["login"]["status"]) && $_SESSION["login"]["status"] == 1) {
    if (isset($_GET["profile"])) {
        include("pages/components/profile.php");
    } else if (isset($_GET["home"])) {
        include("pages/components/hero.php");
    } else if (isset($_GET["edit-profile"])) {
        include("pages/components/edit_profile.php");
    } else if (isset($_GET["edit-password"])) {
        include("pages/components/edit_password.php");
    } else {
        echo "<h1 style='text-align:center; margin: 2.5rem;'>404 Page not found</h1>";
    }
} else {
    if (isset($_GET["login"])) {
        include("pages/components/login.php");
    }

    if (isset($_GET["register"])) {
        include("pages/components/register.php");
    }

    if (isset($_GET["forgot"])) {
        include("pages/components/forgot.php");
    }

    if (isset($_GET["home"])) {
        include("pages/components/hero.php");
    }
}







// This is the foot of the html
include("pages/components/footer.php");
include("pages/components/foot.php");
