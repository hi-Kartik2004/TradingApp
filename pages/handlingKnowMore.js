// Function to handle "know-more" request
console.log("inside");
const fetchUrl = "https://latest-stock-price.p.rapidapi.com/any";
const options = {
  method: "GET",
  headers: {
    "X-RapidAPI-Key": "",
    "X-RapidAPI-Host": "latest-stock-price.p.rapidapi.com",
  },
};

function handleKnowMoreRequest(identifier) {
  const knowMoreUrl = `https://latest-stock-price.p.rapidapi.com/any?Identifier=${identifier}`;

  showLoadingModal(); // Show the loading modal

  const fetchData = () => {
    fetch(knowMoreUrl, options)
      .then((response) => response.json())
      .then((result) => {
        hideLoadingModal(); // Hide the loading modal

        console.log("Displaying details in modal");
        console.log(result); // Log the result in the console

        if (result.length > 0) {
          // Display the details in a modal
          displayModal(result);
          //   // Update the stock price in the table
          //   const identifier = result[0].identifier;
          //   const stockPriceElement = document.querySelector(
          //     `td[data-identifier="${identifier}"] #stock-price-${identifier}`
          //   );
          //   stockPriceElement.textContent = result[0].open; // Assuming the stock price is in the "open" property
        } else {
          // Retry fetching data after a delay
          setTimeout(fetchData, 1000); // Adjust the delay as needed
        }
      })
      .catch((error) => {
        hideLoadingModal(); // Hide the loading modal
        console.error(error);
      });
  };

  fetchData();
}

// Function to display the details in a modal
function displayModal(details) {
  // Extract the required details from the data
  const {
    lastPrice,
    dayHigh,
    dayLow,
    identifier,
    lastUpdateTime,
    open,
    pChange,
    perChange30d,
    perChange365d,
    previousClose,
    symbol,
    totalTradedValue,
    totalTradedVolume,
    yearHigh,
    yearLow,
  } = details.length > 0 ? details[0] : {};

  // Update the modal HTML with the fetched details
  const modalTitle = document.getElementById("modal-title");
  const modalDetails = document.getElementById("modal-details");
  modalTitle.textContent = identifier || "No Data";
  modalDetails.innerHTML = `
    <p>Last Price: ${lastPrice || "-"}</p>
    <p>Day High: ${dayHigh || "-"}</p>
    <p>Day Low: ${dayLow || "-"}</p>
    <p>Last Update Time: ${lastUpdateTime || "-"}</p>
    <p>Open: ${open || "-"}</p>
    <p>Percentage Change: ${pChange || "-"}</p>
    <p>Percentage Change (30 days): ${perChange30d || "-"}</p>
    <p>Percentage Change (365 days): ${perChange365d || "-"}</p>
    <p>Previous Close: ${previousClose || "-"}</p>
    <p>Symbol: ${symbol || "-"}</p>
    <p>Total Traded Value: ${totalTradedValue || "-"}</p>
    <p>Total Traded Volume: ${totalTradedVolume || "-"}</p>
    <p>Year High: ${yearHigh || "-"}</p>
    <p>Year Low: ${yearLow || "-"}</p>
  `;

  // Show the modal
  const modal = document.getElementById("modal");
  modal.style.display = "block";
}

// Function to show the loading modal
function showLoadingModal() {
  const modalTitle = document.getElementById("modal-title");
  const modalDetails = document.getElementById("modal-details");
  modalTitle.textContent = "Loading...";
  modalDetails.innerHTML = ""; // Clear previous details
  const modal = document.getElementById("modal");
  modal.style.display = "block";
}

// Function to hide the loading modal
function hideLoadingModal() {
  const modal = document.getElementById("modal");
  modal.style.display = "none";
}

// Close the modal when the close button is clicked
const closeButton = document.querySelector(".close");
closeButton.addEventListener("click", () => {
  hideModal();
});

// Close the modal when the user clicks outside the modal content
window.addEventListener("click", (event) => {
  const modal = document.getElementById("modal");
  if (event.target === modal) {
    hideModal();
  }
});

// Function to hide the modal
function hideModal() {
  const modal = document.getElementById("modal");
  modal.style.display = "none";
}

// Check if the URL has the "know-more" parameter and call the function with the identifier
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.has("know-more")) {
  const identifier = urlParams.get("know-more");
  handleKnowMoreRequest(identifier);
}

// Function to sanitize the identifier for use in the selector
function sanitizeIdentifier(identifier) {
  // Replace spaces and special characters with hyphens
  return identifier.replace(/\s+/g, "-").replace(/[^a-zA-Z0-9-]/g, "");
}

// Function to fetch the current stock price for a specific identifier
// Function to fetch the current stock price for a specific identifier




// Loop through the identifiers array and fetch stock prices for each identifier
for (let i = 0; i < identifiers.length; i++) {
  const identifier = identifiers[i];
  fetchCurrentSpecific(identifier);
}

