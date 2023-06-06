<?php
// starting session for handling errors;

session_start();

// This the head of the html
include("pages/components/head.php");

// All the components go below.
include("pages/components/navbar.php");

if (isset($_GET["login"])) {
    include("pages/components/login.php");
}

if (isset($_GET["register"])) {
    include("pages/components/register.php");
}

if (isset($_GET["home"])) {
    include("pages/components/hero.php");
}




// This is the foot of the html
include("pages/components/footer.php");
include("pages/components/foot.php");
