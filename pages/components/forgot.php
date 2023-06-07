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
    <div class="login__card">
        <div class="card__heading">
            <h4><u>Forgot Password</u></h4>

        </div>

        <div class="login__form">
            <form action="php/actions.php?forgot" method="post" class="form">
                <div class="email">
                    <h5><i class='bx bx-male'></i> Email Address</h5>
                    <input type="email" placeholder="Email" name="email" required>
                    <!-- <span>We'll never share your email with anyone else</span> -->
                </div>
                <div class="btn__wrapper">
                    <button class="green__btn" type="submit">
                        Send mail
                    </button>

                    <a href="?login">Login?</a>

                </div>

            </form>
        </div>
    </div>
</div>