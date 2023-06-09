// ================== Loading Animation ====================
window.addEventListener("load", function () {
  // Page has finished loading, hide the loader
  var loader = document.getElementById("page-loader");
  loader.style.display = "none";
});

//================== API ===============
const fetchUrl = "https://latest-stock-price.p.rapidapi.com/any";
const options = {
  method: "GET",
  headers: {
    "X-RapidAPI-Key": "",
    "X-RapidAPI-Host": "latest-stock-price.p.rapidapi.com",
  },
};

function populateCards(data) {
  const container = document.getElementById("hero-stocks");

  // Remove existing cards
  container.innerHTML = "";

  // Loop through the first 16 responses and populate the cards
  for (let i = 0; i < 16 && i < data.length; i++) {
    const card = document.createElement("div");
    card.classList.add("stocks__card");
    card.innerHTML = `
      <div class="card__top">
          <div>
              <i class='bx bxl-bitcoin'></i>
          </div>
          <div class="card__heading">
              <h6>${data[i].symbol}</h6>
              <span>${data[i].identifier}</span>
          </div>
      </div>
      <div class="card__bottom">
          <h6>Rs ${data[i].open}</h6>
          <h4>(${data[i].pChange}%)</h4>
      </div>
    `;

    if (data[i].pChange < 0) {
      card.querySelector(".card__bottom h4").classList.add("red__text");
    } else if (data[i].pChange > 0) {
      card.querySelector(".card__bottom h4").classList.add("green__text");
    }

    container.appendChild(card);
  }

  hideLoadingAnimation();
}

function populateTable(data) {
  // Populating the table
  const table = document.querySelector(".buy__scrollable table");
  table.innerHTML = ""; // Clear existing table rows

  const tableBody = document.createElement("tbody");
  tableBody.innerHTML = `
    <tr class="sticky">
      <th class="hide-mobile">Name</th>
      <th>Open at</th>
      <th>Change (in %)</th>
      <th>Year High</th>
      <th>Year Low</th>
      <th>Trade Value</th>
      <th>Buy Stock</th>
    </tr>
  `;

  // Sort the data based on the 'filter' query parameter
  const urlParams = new URLSearchParams(window.location.search);
  const filterParam = urlParams.get("filter");
  if (filterParam === "ascending") {
    data.sort((a, b) => a.open - b.open);
  } else if (filterParam === "descending") {
    data.sort((a, b) => b.open - a.open);
  }

  for (let i = 0; i < data.length; i++) {
    const tableRow = document.createElement("tr");
    tableRow.innerHTML = `
      <td>${data[i].symbol}</td>
      <td class="green__text">Rs ${data[i].open}/-</td>
      <td class="hide-mobile">${data[i].pChange}%</td>
      <td>Rs ${data[i].yearHigh}/-</td>
      <td>Rs ${data[i].yearLow}/-</td>
      <td>Rs ${data[i].totalTradedValue}/-</td>
      <td>
        <div class="buy__td">
          <a href="/php/actions.php?add=${data[i].identifier}&symbol=${data[i].symbol}&price=${data[i].open}" class="green__btn">Buy</a>
          <a href="?sell&know-more=${data[i].identifier}">Know more</a>
        </div>
      </td>
    `;
    tableBody.appendChild(tableRow);
  }

  table.appendChild(tableBody); // Append the new table rows
}

// =============== fetch Data =============
function showLoadingAnimation() {
  const loaders = document.querySelectorAll(".add-loader");
  loaders.forEach((loader) => {
    loader.classList.add("loading");
  });
}

function hideLoadingAnimation() {
  const loaders = document.querySelectorAll(".add-loader");
  loaders.forEach((loader) => {
    loader.classList.remove("loading");
  });
}

function fetchData() {
  showLoadingAnimation();
  try {
    fetch(fetchUrl, options)
      .then((response) => response.json())
      .then((result) => {
        if (result.length != 0) {
          hideLoadingAnimation();
          console.log("populating the cards");
          console.log(result); // Log the result in the console

          if (result.length > 0) {
            populateCards(result);
          }
        }
      })
      .catch((error) => {
        hideLoadingAnimation();
        console.error(error);
      });
  } catch (error) {
    hideLoadingAnimation();
    console.error(error);
  }
}

// ================ Fetch Data for table ===============
let animationCount = 0;
function fetchDataForTable() {
  try {
    fetch(fetchUrl, options)
      .then((response) => response.json())
      .then((result) => {
        if (result.length != 0) {
          console.log("Populating the table");
          console.log(result); // Log the result in the console

          if (result.length > 0) {
            populateTable(result);
          }
        }
      })
      .catch((error) => {
        console.error(error);
      });
  } catch (error) {
    console.error(error);
  }
}

// Call the fetchData function initially when the page loads
window.addEventListener("load", fetchData);
// document.addEventListener("load", fetchDataForTable);

// Call the fetchData function every 2 seconds
setInterval(fetchData, 2000);
setInterval(fetchDataForTable, 2000);
