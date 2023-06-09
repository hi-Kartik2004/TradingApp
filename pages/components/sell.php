<?php
require_once("php/config.php");
$conn = mysqli_connect(server, host, password, db_name);

?>
<!-- Modal -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 id="modal-title"></h2>
        <div id="modal-details"></div>
    </div>
</div>


<section class="buy__stocks__wrapper">
    <div class="buy__stocks container">

        <div class="buy__heading">
            <h3><u>Your Stocks</u></h3>
            <br>
            <div class="filter__wrapper">
                <span>Filter you stock here</span>
                <div id="filter-wrapper" class="relative dropdown__wrapper">
                    <p id="filter-link" class="dropdown-link">Filter <i class='bx bx-chevron-down'></i></p>
                    <div id="filter-dropdown" class="none dropdown">
                        <a href="?filter=relevance">Relevance</a>
                        <a href="?filter=ascending">Ascending</a>
                        <a href="?filter=ascending">Recending</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="buy__content  ">
            <div class="buy-wrapper ">
                <!-- <h4 style="text-align:center"><u>Other Details</u></h4> -->
                <br>
                <div class="scrollable buy__scrollable sticky">
                    <!-- Loading animation -->
                    <!-- <div class="loading-animation ">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </div> -->
                    <table class="responsive">
                        <tbody>
                            <tr class="sticky">
                                <th class="hide-mobile">id</th>
                                <th>Stock Identifier</th>
                                <th>Quantity</th>
                                <th>Bought At</th>
                                <th>Current Price</th>
                                <!-- <th>Trade Value</th> -->
                                <th>
                                    Sell Stock
                                </th>
                                <!-- <th>Change per <br>month</th>
                                <th>Change per <br>Year</th> -->
                                <!-- <th>E-mail</th> -->
                            </tr>
                            <?php
                            global $conn; // Assuming the database connection is available

                            $email = $_SESSION["login"]["data"]["email"]; // Replace $email with the actual email value

                            // Fetch details from the transactions table
                            $query = "SELECT * FROM `transactions` WHERE `email` = ? AND `status` = 1";
                            $stmt = mysqli_prepare($conn, $query);
                            mysqli_stmt_bind_param($stmt, "s", $email);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);

                            // Check if there are any rows returned
                            if (mysqli_num_rows($result) > 0) {
                                // Initialize an empty array to store identifiers
                                $identifiers = [];

                                // Loop through the rows and populate the HTML template
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $id = $row['id'];
                                    $identifier = $row['identifier'];
                                    $quantity = $row['quantity'];
                                    $stockPrice = $row['stock_price'];
                                    $transactionAmt = $row['transaction_amt'];

                                    // Output the HTML template with the fetched values
                                    echo '<tr>';
                                    echo '<td>' . $id . '</td>';
                                    // echo '<td class="">' . $email . '</td>';
                                    echo '<td class="hide-mobile">' . $identifier . '</td>';
                                    echo '<td>' . $quantity . '</td>';
                                    echo '<td>Rs ' . $stockPrice . '/-</td>';
                                    echo '<td data-identifier="' . $identifier . '">Rs <span id="stock-price-' . $identifier . '">-</span>/-</td>';
                                    echo '<td>';
                                    echo '<div class="buy__td">';
                                    echo '<a href="#" id="sell-link-' . $identifier . '" class="green__btn">sell</a>';
                                    echo '<a href="?sell&know-more=' . $identifier . '">Know more</a>';
                                    echo '</div>';

                                    echo '</td>';
                                    echo '</tr>';

                                    // Add the identifier to the identifiers array
                                    $identifiers[] = $identifier;
                                }
                            } else {
                                // No matching rows found
                                echo '<tr>';
                                echo '<td colspan="7">No Stocks found</td>';
                                echo '</tr>';
                            }

                            // Pass the identifiers array to the JavaScript function
                            echo '<script>var identifiers = ' . json_encode($identifiers) . ';</script>';
                            echo '<script src="pages/handlingKnowMore.js"></script>';

                            ?>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
</section>
<script>
    // Function to sanitize the identifier for use in the selector
    function sanitizeIdentifier(identifier) {
        // Replace spaces and special characters with hyphens
        return identifier.replace(/\s+/g, "-").replace(/[^a-zA-Z0-9-]/g, "");
    }

    // Function to fetch the current stock price for a specific identifier
    function fetchCurrentSpecific(identifier) {
        const knowMoreUrl = `https://latest-stock-price.p.rapidapi.com/any?Identifier=${encodeURIComponent(
    identifier
  )}`;

        fetch(knowMoreUrl, options)
            .then((response) => response.json())
            .then((result) => {
                console.log("Displaying details in modal");
                console.log(result); // Log the result in the console

                if (result.length > 0) {
                    // Update the stock price in the table
                    const stockPriceElement = document.querySelector(
                        `td[data-identifier="${CSS.escape(
            identifier
          )}"] span[id^="stock-price-"]`
                    );
                    if (stockPriceElement) {
                        stockPriceElement.textContent = result[0].open; // Assuming the stock price is in the "open" property
                        updateSellLinkHref(identifier, result[0].open); // Update the href attribute
                    } else {
                        console.error(
                            `Stock price element not found for identifier: ${identifier}`
                        );
                    }
                } else {
                    // Retry fetching data after a delay
                    setTimeout(() => fetchCurrentSpecific(identifier), 1000); // Adjust the delay as needed
                }
            })
            .catch((error) => {
                console.error(error);
            });
    }

    // Function to update the href attribute of the "sell" link
    function updateSellLinkHref(identifier, stockPrice) {
        const sellLink = document.getElementById(`sell-link-${identifier}`);
        if (sellLink) {
            const spanValue = document.getElementById(`stock-price-${identifier}`).textContent;
            const href = `php/actions.php?sell=${encodeURIComponent(spanValue)}&identifier=<?php echo $identifier; ?>&quantity=<?php echo $quantity; ?>&stockPrice=<?php echo $stockPrice; ?>`;

            sellLink.href = href;
        }
    }


    // Loop through the identifiers array and fetch stock prices for each identifier
    for (let i = 0; i < identifiers.length; i++) {
        const identifier = identifiers[i];
        fetchCurrentSpecific(identifier);
    }
</script>