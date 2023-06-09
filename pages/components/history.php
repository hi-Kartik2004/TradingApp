<?php
require_once("php/config.php");
$conn = mysqli_connect(server, host, password, db_name);
$email = $_SESSION["login"]["data"]["email"];
// Accessing the user_details for login history;
$selectQuery = "SELECT * FROM `transactions` WHERE `email` = '$email';";
$result = mysqli_query($conn, $selectQuery);
if ($result) {
    $transactionHistory = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
}

?>

<section class="buy__stocks__wrapper flex-col">
    <div class="" id="error">
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
    <div class="buy__stocks container">

        <div class="buy__heading">
            <h3><u>Transaction History!</u></h3>
            <br>
            <div class="filter__wrapper">
                <span>Your current Balance is: </span>
                <h6 class="green__text">Rs <?= $_SESSION["login"]["data"]["balance"] ?>/-</h6>
                <!-- <div id="filter-wrapper" class="relative dropdown__wrapper">
                    <p id="filter-link" class="dropdown-link">Filter <i class='bx bx-chevron-down'></i></p>
                    <div id="filter-dropdown" class="none dropdown">
                        <a href="?filter=relevance">Relevance</a>
                        <a href="?filter=ascending">Ascending</a>
                        <a href="?filter=ascending">Recending</a>
                    </div>
                </div> -->
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
                                <th class="">identifier</th>
                                <th>Transaction Ammount</th>
                                <!-- <th>Change (in %)</th> -->
                                <th>Transaction Time (UST)</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <!-- <th>
                                    Buy Stock
                                </th> -->
                                <!-- <th>Change per <br>month</th>
                                <th>Change per <br>Year</th> -->
                                <!-- <th>E-mail</th> -->
                            </tr>
                            <?php
                            $transactionHistory = array_reverse($transactionHistory);

                            if (empty($transactionHistory)) {
                                echo '<tr>
                                   <td>No Transactions done</td>
                               </tr>';
                            }

                            foreach ($transactionHistory as $transaction) {
                                $statusMessage = '';

                                switch ($transaction["status"]) {
                                    case 0:
                                        $statusMessage = 'Transaction Failed';
                                        break;
                                    case 1:
                                        $statusMessage = 'Debited';
                                        break;
                                    case -1:
                                        $statusMessage = 'Credited';
                                        break;
                                    case -2:
                                        $statusMessage = 'Sold';
                                        break;
                                    default:
                                        $statusMessage = 'Unknown';
                                        break;
                                }

                                echo '<tr>
                                   <td>' . $transaction["identifier"] . '</td>
                                   <td class="">' . $transaction["transaction_amt"] . '</td>
                                   <td>' . $transaction["created_at"] . '</td>
                                   <td>' . $transaction["quantity"] . '</td>
                                   <td>' . $statusMessage . '</td>
                               </tr>';
                            }


                            ?>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
</section>

<script>
    // =========== Handling error banner ==========
    const error = document.getElementById("error");
    const errorContent = error.innerHTML;
    console.log(error);
    if (errorContent == "" || errorContent == " ") {
        console.log("is empty");
        error.style.padding = "0";
    }
</script>