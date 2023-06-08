//================== API ===============
const fetchUrl = "https://latest-stock-price.p.rapidapi.com/any";
const options = {
  method: "GET",
  headers: {
    "X-RapidAPI-Key": "05a1d027bcmsh4696fdb923bcd99p1b1584jsnc0eb4d43fc88",
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
    card.className = "stocks__card";
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
          <h4 class="${data[i].pChange < 0 ? "red__text" : "green__text"}">(${
      data[i].pChange
    }%)</h4>
        </div>
      `;
    container.appendChild(card);
  }
}

function fetchData() {
  try {
    fetch(fetchUrl, options)
      .then((response) => response.json())
      .then((result) => {
        console.log(result); // Log the result in the console
        if (result.length > 0) {
          populateCards(result);
        }
      })
      .catch((error) => console.error(error));
  } catch (error) {
    console.error(error);
  }
}

// Call the fetchData function initially when the page loads
window.addEventListener("load", fetchData);

// Call the fetchData function every 10 seconds
setInterval(fetchData, 8000);
