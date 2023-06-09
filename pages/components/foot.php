<?php
if (isset($_GET["buy"])) {

    echo '<script src="pages/api.js"></script>';
}

if (isset($_GET["home"])) {
    echo '<script src="pages/api.js"></script>';
}
?>
<!--<script src="pages/api.js"></script>-->
<?php
if (isset($_GET["know-more"])) {
    echo '<script src="pages/handlingKnowMore.js"></script>';
}

if (isset($_GET["buy"])) {
    echo '<script src="pages/handlingKnowMore.js"></script>';
}
?>

<script src="pages/main.js"></script>
<script src="pages/main2.js"></script>
<script src="pages/handlingError.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

<!-- Handling total Amount calculations -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Calculate Total Amount
        function calculateTotalAmount() {
            console.log("calculateTotalAmount function called.");

            var cards = document.getElementsByClassName("cart__card");
            var totalAmount = 0;
            var params = ""; // Parameters for the GET request

            for (var i = 0; i < cards.length; i++) {
                var card = cards[i];
                var identifierElement = card.querySelector(".cart__card__heading span");
                var priceElement = card.querySelector(".cart__card__bottom .cart__price__wrapper h6");
                var quantityElement = card.querySelector(".cart__card__bottom .cart__modifier__btns input[type='number']");

                if (!identifierElement) {
                    console.log("Identifier element not found for card " + (i + 1));
                    continue;
                }

                if (!priceElement) {
                    console.log("Price element not found for card " + (i + 1));
                    continue;
                }

                if (!quantityElement) {
                    console.log("Quantity element not found for card " + (i + 1));
                    continue;
                }

                var identifier = identifierElement.textContent;
                var price = parseFloat(priceElement.textContent.replace("Rs ", ""));
                var quantity = parseFloat(quantityElement.value);

                var subtotal = price * quantity;
                console.log("Card " + (i + 1) + " subtotal: " + subtotal);

                // Exclude cards with display: none from the total amount calculation
                if (window.getComputedStyle(card).display !== "none") {
                    totalAmount += subtotal;
                }

                // Append parameters for each visible card
                params +=
                    "&identifier" +
                    (i + 1) +
                    "=" +
                    encodeURIComponent(identifier) +
                    "&quantity" +
                    (i + 1) +
                    "=" +
                    encodeURIComponent(quantity) +
                    "&amount" +
                    (i + 1) +
                    "=" +
                    encodeURIComponent(price);
            }

            var totalAmountElement = document.getElementById("totalAmount");
            totalAmountElement.textContent = totalAmount.toFixed(2);

            // Update make payment URL with totalAmount and card parameters
            var makePaymentBtn = document.getElementById("makePaymentBtn");
            makePaymentBtn.href = "/php/actions.php?make-payment=" + totalAmount.toFixed(2) + params;
        }

        // Call calculateTotalAmount function initially and whenever quantity changes
        calculateTotalAmount();

        var quantityInputs = document.querySelectorAll(".cart__card__bottom .cart__modifier__btns input[type='number']");
        for (var i = 0; i < quantityInputs.length; i++) {
            quantityInputs[i].addEventListener("change", calculateTotalAmount);
        }
    });
</script>

<!-- Initialize Swiper -->
<script>
    const progressCircle = document.querySelector(".autoplay-progress svg");
    const progressContent = document.querySelector(".autoplay-progress span");
    var swiper = new Swiper(".mySwiper", {
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: {
            delay: 2500,
            disableOnInteraction: false
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev"
        },
        on: {
            autoplayTimeLeft(s, time, progress) {
                progressCircle.style.setProperty("--progress", 1 - progress);
                progressContent.textContent = `${Math.ceil(time / 1000)}s`;
            }
        }
    });
</script>

<script>
    // =========== Handlin page loader =============
    document.addEventListener("DOMContentLoaded", function() {
        // Stop loader animation and hide loader
        function stopLoader() {
            var loader = document.querySelector("#page-loader");
            loader.style.display = "none";
        }

        // Call stopLoader function when page has finished loading
        window.addEventListener("load", stopLoader);
    });
</script>

<script src="
https://cdn.jsdelivr.net/npm/locomotive-scroll@4.1.4/dist/locomotive-scroll.min.js
"></script>

</body>

</html>