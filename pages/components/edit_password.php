<div class="login__wrapper">
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

    <div class="register__card">
        <div class="card__heading">
            <h4><u>Edit Password</u></h4>

        </div>

        <hr>

        <div class="login__form">
            <form action="php/actions.php?edit-password" method="post" class="form">
                <div>
                    <h6>Old Password*</h6>
                    <input type="password" name="oldPassword" placeholder="password" required>
                </div>
                <div class="password">

                    <div class="register__firstline" style="flex-direction: column;">
                        <div>
                            <h6> Password*</h6>
                            <input type="password" name="newPassword" placeholder="password" required>
                        </div>

                        <div>
                            <h6> Confirm Password*</h6>
                            <input type="password" name="cpassword" placeholder="Confirm Password" required>
                        </div>
                    </div>

                    <span>We'll never share your email with anyone else</span>

                </div>

                <div class="login">
                    <div class="checkbox__div">
                        <input type="checkbox" id="checkbox" required>
                        <span id="remember-me">I agree with your Privacy Policy and Terms and Conditions.</span>
                    </div>
                    <!-- <span>Your Password at the site is Encrypted And Secured</span> -->
                    <!-- <a href="?forgot"></a> -->
                </div>
                <div class="register__btn__wrapper">
                    <button class="register__green__btn" type="submit">
                        Edit Profile
                    </button>
                    <hr>
                    <a href="?edit-password"><u>Edit Password?
                        </u></a>

                </div>

            </form>
        </div>
    </div>
</div>