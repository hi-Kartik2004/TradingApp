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


