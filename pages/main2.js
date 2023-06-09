// ===================== Copy to clipboard ==============

function copyToClipboard() {
  console.log("copy to clipboard called.");
  const input = document.getElementById("affiliate-link");
  const text = input.value;

  navigator.clipboard
    .writeText(text)
    .then(() => {
      alert("Copied to clipboard!");
    })
    .catch((error) => {
      console.error("Failed to copy to clipboard:", error);
    });
}

const copyButton = document.getElementById("copy-btn");
copyButton.addEventListener("click", copyToClipboard);

// =================== Handling total amount ============
document.addEventListener("DOMContentLoaded", function () {
  // Calculate Total Amount
  function calculateTotalAmount() {
    console.log("Function called.");
    var cards = document.getElementsByClassName("cart__card");
    var totalAmount = 0;

    for (var i = 0; i < cards.length; i++) {
      var card = cards[i];
      var priceElement = card.querySelector(
        ".cart__card__bottom .cart__price__wrapper h6"
      );
      var quantityElement = card.querySelector(
        ".cart__card__bottom .cart__modifier__btns input[type='number']"
      );

      var price = parseFloat(priceElement.textContent.replace("Rs ", ""));
      var quantity = parseFloat(quantityElement.value);

      totalAmount += price * quantity;
    }

    var totalAmountElement = document.getElementById("totalAmount");
    totalAmountElement.textContent =
      "Total Amount: Rs " + totalAmount.toFixed(2);
  }

  // Call calculateTotalAmount function initially and whenever quantity changes
  calculateTotalAmount();

  var quantityInputs = document.querySelectorAll(
    ".cart__card__bottom .cart__modifier__btns input[type='number']"
  );
  for (var i = 0; i < quantityInputs.length; i++) {
    quantityInputs[i].addEventListener("change", calculateTotalAmount);
  }
});
