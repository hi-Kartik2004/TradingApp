console.log("Js has been loaded");

//============ Navbar dropdown toggle =============
const financeLink = document.getElementById("finance-link");
const tradeLink = document.getElementById("trade-link");
const reportLink = document.getElementById("report-link");

const allDropDownLinks = document.querySelectorAll(".dropdown-link");
console.log(allDropDownLinks);

const allDropDowns = document.querySelectorAll(".dropdown");
console.log(allDropDowns);

for (let i = 0; i < allDropDownLinks.length; i++) {
  allDropDownLinks[i].addEventListener("click", (e) => {
    allDropDowns[i].classList.toggle("none");
    for (let j = 0; j < allDropDownLinks.length; j++) {
      if (j == i) {
      } else {
        allDropDowns[j].classList.add("none");
      }
    }
  });
}

// ============= mobile Nav ===========

const mobileNav = document.getElementById("mobile-nav");
const hamburger = document.getElementById("hamburger");

hamburger.addEventListener("click", () => {
  mobileNav.classList.toggle("none");
});

// =============== Handling url ==============

// console.log("Hi testing");
const url = window.location.href;
const baseUrl = url.split("?")[0]; // Get the base URL without the query string

if (!url.includes("?")) {
  const newUrl = `${baseUrl}?home=firstTime`;
  window.location.href = newUrl;
}

// ============= Handling remember me ============
const rememberMe = document.getElementById("remember-me");
const checkbox = document.getElementById("checkbox");

rememberMe.addEventListener("click", () => {
  checkbox.checked = !checkbox.checked;
});

