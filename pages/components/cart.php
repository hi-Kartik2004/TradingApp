<?php

require_once("php/config.php");
$conn = mysqli_connect(server, host, password, db_name);
$email = $_SESSION["login"]["data"]["email"];
$selectQuery = "SELECT * FROM `cart` WHERE `email` = '$email'";
$run = mysqli_query($conn, $selectQuery);
if ($run) {
    if (mysqli_num_rows($run) > 0) {
        $cartData = mysqli_fetch_all($run, MYSQLI_ASSOC);
    } else {
        $_SESSION["err"]["err_msg"] = "Your cart is Empty";
    }
} else {
    $_SESSION["err"]["err_msg"] = "Not able to connect with Database - from cart";
}
?>

<div class="cart__sections__wrapper relative">
    <div class="green__text" id="error">
        <?php
        if (isset($_SESSION["err"]["err_msg"])) {
            if (isset($_SESSION["err"]["err_msg"]["payment"])) {
                echo "
    <div class='err__content'>
        <i class='bx bx-error-circle'></i>
        " . $_SESSION['err']['err_msg']["payment"] . "
    </div>
";
                unset($_SESSION["err"]);
            } else {
                echo "
            <div class='err__content'>
                <i class='bx bx-error-circle'></i>
                " . $_SESSION['err']['err_msg'] . "
            </div>
        ";
                unset($_SESSION["err"]);
            }
        }
        ?>
    </div>
    <br>
    <div class="container align__me__along__x">
        <section class="cart__wrapper">
            <div class="cart container">
                <?php
                if (!empty($cartData) && isset($cartData)) {
                    $cartData = array_reverse($cartData);
                    foreach ($cartData as $cartItem) {
                        $identifier = $cartItem["identifier"];
                        $symbol = $cartItem["symbol"];
                        $price = $cartItem["price"];
                        echo '
                <div class="cart__card">
                    <div class="cart__card__top">
                        <div class="cart__card__heading">
                            <h4>' . $symbol . '</h4>
                            <span>' . $identifier . '</span>
                        </div>
                    </div>
                    <div class="cart__card__bottom">
                        <div class="cart__price__wrapper"> 
                            <h6 class="green__text">Rs ' . $price . '</h6>
                            <a href="php/actions.php/?delete=' . $identifier . '" class="red__text delete-btn" data-identifier="' . $identifier . '">Delete Item</a>
                        </div>
                        <div class="cart__modifier__btns">
                            <span>Quantity</span>
                            <input type="number" min="1" value="1">
                        </div>
                    </div>
                </div>';
                    }
                }
                ?>
            </div>
        </section>

        <section class="payment__details__wrapper absolute">
            <div class="payment__details container">
                <div class="cart__card payment__card" style="  max-width: 350px; width: 100%;">
                    <div class="cart__card__top">
                        <div class="cart__card__heading">
                            <h4>Total</h4>
                            <h5 class="green__text" id="totalAmount">Rs </h5>
                        </div>
                    </div>
                    <div class="cart__card__bottom">
                        <div class="cart__price__wrapper">
                            <a href=<?= '/php/actions.php?delete-item=all' ?> class="red__text">Clear Cart</a>
                        </div>
                        <div class="cart__modifier__btns payment__modifiers">
                            <!-- <span>Total Quantity</span>
                            <input type="number" min="1" value="1"> -->
                        </div>
                    </div>
                    <a href="<?= '/php/actions.php?make-payment=' ?>" id="makePaymentBtn" class="hero__green__btn long__btn">Make Payment</a>

                </div>
            </div>
        </section>
    </div>
</div>


<?php
echo '<script>
    // =========== Handling error banner ==========
    const error = document.getElementById("error2");
    const errorContent = error.innerHTML;
    console.log(error);
    if (errorContent == "" || errorContent == " ") {
        console.log("is empty");
        error.style.padding = "0rem";
    }
</script>';
?>

<!-- <script>
    // Add event listeners to the delete buttons
    const deleteButtons = document.querySelectorAll('.delete-btn');
    const cartCards = document.querySelectorAll('.cart__card');
    console.log(cartCards);
    for (let i = 0; i < deleteButtons.length; i++) {
        deleteButtons[i].addEventListener("click", () => {
            cartCards[i].classList.add("none");
        });
    }
</script> -->