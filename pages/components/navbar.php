<!-- <h1>This is navbar</h1> -->

<section class="navbar">


    <div class="navbar__wrapper">
        <div class="logo__div">
            <img src="pages/img/logo4.png" alt="logo">
        </div>

        <div class="nav__links">
            <a href="?home">Home</a>
            <a href="?exchange">Exchange</a>

            <!-- Finance dropdown -->
            <div id="finance-wrapper" class="relative dropdown__wrapper">
                <p id="finance-link" class="dropdown-link">Finance <i class='bx bx-chevron-down'></i></p>
                <div id="finance-dropdown" class="none dropdown">
                    <a href="?buy">Buy Stocks</a>
                    <a href="?sell">Sell Stocks</a>
                </div>
            </div>

            <!-- Trade dropdown -->
            <div id="trade-wrapper" class="relative dropdown__wrapper">
                <p id="trade-link" class="dropdown-link">Trade <i class='bx bx-chevron-down'></i></p>
                <div id="trade-dropdown" class="none dropdown">
                    <a href="?stocks">Open Order</a>
                    <a href="?history">Trade History</a>
                </div>
            </div>

            <!-- <a href="#" id="report-link">Report <i class='bx bx-chevron-down'></i></a> -->
            <a href="?login">Login</a>
            <a href="?register">Register</a>
            <div class="profile__link none">
                <a href="?user=temp">profile</a>
            </div>
        </div>

        <div class="hamburger none" id="hamburger">
            <i class='bx bx-menu-alt-left'></i>
        </div>

        <div class="mobile__nav none" id="mobile-nav">
            <a href="?home">Home</a>
            <a href="?exchange">Exchange</a>
            <a href="?buy">Buy Stocks</a>
            <a href="?sell">Sell Stocks</a>
            <a href="?stocks">Open order</a>
            <a href="?history">Trade History</a>
            <a href="?login">Login</a>
            <a href="?register">Register</a>
            <a href="?profile">[profile]</a>
        </div>

    </div>
</section>