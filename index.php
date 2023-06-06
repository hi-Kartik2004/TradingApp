<?php

// This the head of the html
include("pages/components/head.php");

// All the components go below.
include("pages/components/navbar.php");

if (isset($_GET["login"])) {
    include("pages/components/login.php");
}




// This is the foot of the html
include("pages/components/foot.php");
