<section class="profile">
    <div class="profile__wrapper container">
        <div class="error" id="error"><?php
                                        if (isset($_SESSION["err"]["err_msg"])) {
                                            echo "
            <div class='err__content'>
                <i class='bx bx-error-circle'></i>
                " . $_SESSION['err']['err_msg'] . "
            </div>
        ";
                                            unset($_SESSION["err"]);
                                        }
                                        ?></div>
        <br>
        <div class="profile__heading container">
            <div class="flex info__cell">
                <h3 class="">Hi </h3>
                <h3 class="green__text"><?= $_SESSION["login"]["data"]["first_name"] ?>!</h3>
            </div>
            <div class="affiliate__link">
                <span>Affiliate Link</span>
                <div class="flex container">
                    <?php
                    require_once("php/config.php");
                    $email = $_SESSION["login"]["data"]["email"];
                    ?>
                    <input type="text" value="<?php echo domain . "?affiliate=" . $email ?>" id="affiliate-link" class="long__input">
                    <button class="green__btn copy__btn" id="copy-btn">Copy</button>

                </div>

            </div>
        </div>

        <div class="profile__cards__wrapper">



            <div class="user__info">
                <div>
                    <h4>User Details</h4>
                    <hr>
                </div>


                <div>
                    <div class="info__cell flex">
                        <h6>User Id: </h6>
                        <h6 class="green__text"><?= $_SESSION["login"]["data"]["id"] ?></h6>
                    </div>

                    <div class="info__cell flex">

                        <h6>First Name: </h6>
                        <h6 class="green__text"><?= $_SESSION["login"]["data"]["first_name"] ?></h6>
                    </div>
                    <div class="info__cell flex">
                        <h6>Last Name: </h6>
                        <h6 class="green__text"><?= $_SESSION["login"]["data"]["last_name"] ?></h6>
                    </div>
                    <div class="info__cell flex">
                        <h6>Email: </h6>
                        <h6 class="green__text"><?= $_SESSION["login"]["data"]["email"] ?></h6>
                    </div>
                    <div class="info__cell flex">
                        <h6>Phone: </h6>
                        <h6 class="green__text"><?= $_SESSION["login"]["data"]["phone"] ?></h6>
                    </div>
                    <div class="info__cell flex">
                        <h6>Balance: </h6>
                        <h6 class="green__text">Rs <?= $_SESSION["login"]["data"]["balance"] ?>/-</h6>
                    </div>
                    <div class="info__cell flex">
                        <h6>Language:</h6>
                        <h6 class="green__text">English</h6>
                    </div>
                    <div class="info__cell flex">
                        <h6>KYC Status:</h6>
                        <h6 class="green__text">Verified</h6>
                    </div>
                    <div class="info__cell flex">
                        <h6>Account created: </h6>
                        <h6 class="green__text"><?= $_SESSION["login"]["data"]["created_at"] ?></h6>
                    </div>
                    <div class="info__cell flex">
                        <h6>Last login Time: </h6>
                        <h6 class="green__text"><?= $_SESSION["login"]["data"]["updated_at"] ?></h6>
                    </div>
                    <div class="info__cell flex">
                        <h6>Current IP: </h6>
                        <?php
                        $ip = $_SERVER['REMOTE_ADDR'];
                        $shortIpAddress = substr($ip, 0, 10);
                        ?>

                        <h6 class="green__text"><?= $shortIpAddress ?></h6>
                    </div>

                </div>




                <div class="btn__wrapper info__cell">
                    <button class="hero__green__btn">
                        <a href="?edit-profile">Edit Profile</a>
                    </button>

                    <button class="hero__green__btn">
                        <a href="?edit-password">Edit Password</a>
                    </button>
                </div>


            </div>


            <div class="table-wrapper">
                <h4 style="text-align:center"><u>Other Details</u></h4>
                <br>
                <div class="scrollable">
                    <table class="responsive">
                        <tbody>
                            <tr>
                                <th class="hide-mobile">Access Id</th>
                                <th>Access type</th>
                                <th>IP (10 characters)</th>
                                <th>Last Access Time (UST)</th>
                                <th>Account <br>Status</th>
                                <!-- <th>E-mail</th> -->
                            </tr>
                            <?php
                            if (isset($_SESSION["login"]["loginHistory"])) {
                                $loginHistory = $_SESSION["login"]["loginHistory"];

                                // Check if there is login history data
                                if (!empty($loginHistory)) {
                                    $loginHistory = array_reverse($loginHistory);
                                    foreach ($loginHistory as $login) {
                                        // Access the relevant properties from each login entry
                                        $uniqueId = $login[0] ?? "";
                                        $email = $login[1] ?? "";
                                        $timestamp = $login[2] ?? "";
                                        $accessType = $login[3] ?? "";
                                        $ipAddress = $login[4] ?? "";
                                        $status = $login[5] ?? "";
                                        if ($status == '0') {
                                            $status = "user logged out!";
                                        } else {
                                            $status = "User logged in";
                                        }
                                        $shortIpAddress = substr($ipAddress, 0, 10);

                                        echo '<tr>
                                    <td style= "width: 10px;" class="hide-mobile">' . $uniqueId . '</td>
                                    <td>' . $accessType . '</td>
                                    <td>' . $shortIpAddress . '</td>
                                    <td class="">' . $timestamp . '</td>
                                        <td>' . $status . '</td>
                                    </tr>';
                                    }
                                } else {
                                    $_SESSION["err"]["err_msg"] = "No Previous login History!";
                                    header("location: ../?profile");
                                }
                            }


                            ?>
                            <!-- <tr>
                            <td >$timestamp</td>
                            <td>$accessType</td>
                            <td class="hide-mobile"> $uniqueId</td>
                            <td>$ipAddress</td>
                            <td>$status</td>
                        </tr> -->


                        </tbody>
                    </table>
                </div>
                <div class="pinned">
                    <table>
                        <tbody>
                            <tr>
                                <th>Name</th>
                            </tr>
                            <tr>
                                <td>Gonzalo</td>
                            </tr>
                            <tr>
                                <td>Agustin</td>
                            </tr>
                            <tr>
                                <td>John</td>
                            </tr>
                            <tr>
                                <td>Test</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>



</section>