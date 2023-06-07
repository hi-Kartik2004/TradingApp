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
            <h4>UserName</h4>
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

    </div>



</section>