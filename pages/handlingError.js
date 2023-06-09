// =========== Handling error banner ==========
const error = document.getElementById("error");
const errorContent = error.innerHTML;
console.log(error);
if (errorContent == "" || errorContent == " ") {
  console.log("is empty");
  error.style.padding = "0";
}

