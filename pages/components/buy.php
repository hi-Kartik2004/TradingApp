<!-- Modal HTML -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 id="modal-title"></h2>
        <div id="modal-details"></div>
    </div>
</div>

<section class="buy__stocks__wrapper">
    <div class="buy__stocks container">
        <div class="green__text" id="error">
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

        <div class="buy__heading">
            <h3><u>Stocks to Buy!</u></h3>
            <br>
            <div class="filter__wrapper">
                <span>Filter you stock here</span>
                <div id="filter-wrapper" class="relative dropdown__wrapper">
                    <p id="filter-link" class="dropdown-link">Filter <i class='bx bx-chevron-down'></i></p>
                    <div id="filter-dropdown" class="none dropdown">
                        <a href="?buy&filter=relevance">Relevance</a>
                        <a href="?buy&filter=ascending">Ascending</a>
                        <a href="?buy&filter=descending">Decending</a>
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
                                <th>Name</th>
                                <th>Open at</th>
                                <th class="hide-mobile">Change (in %)</th>
                                <th>Year High</th>
                                <th>Year Low</th>
                                <th>Trade Value</th>
                                <th>
                                    Buy Stock
                                </th>
                                <!-- <th>Change per <br>month</th>
                                <th>Change per <br>Year</th> -->
                                <!-- <th>E-mail</th> -->
                            </tr>
                            <tr>
                                <td>Loading...</td>
                                <td class="">Loading...</td>
                                <td class="hide-mobile"> Loading...</td>
                                <td>Loading...</td>
                                <td>Loading...</td>
                                <td>Loading...</td>
                                <td>
                                    <div class="buy__td">
                                        <a href="?cart" class="green__btn">Buy</a>
                                        <a href="?know-more">Know more</a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
</section>