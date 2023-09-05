<?php extendPage('Globals/box-layout.php'); ?>

<?php blockStart('css')?>
    <link rel="stylesheet" href="/assets/css/default/welcome.css">
<?php blockEnd() ?>

<?php blockStart('body')?>
    <div class="header-main">
        <div class="top-bar">
            <div class="sec">
                <div class="sec-12 menu-sec-wrap">
                    <div class="logo-wrap">
                        <a href="#">
                            <img src="/assets/images/logo/testlogo.png" alt="test-logo">
                        </a>
                    </div>
                    <label class="menu-wrap">
                        <input type="checkbox" id="menu-toggle">
                        <ul class="menu">
                            <li><a href="#">Home</a></li>
                            <li><a href="#">Services</a></li>
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">Contact Us</a></li>
                        </ul>
                        <div class="burger">
                            <i class="fi fi-sr-menu-burger" id="burger-close"></i>
                            <i class="fi fi-sr-cross-small" id="burger-open"></i>
                        </div>
                    </label>
                </div>
            </div>
        </div>
        <div class="header-main-flex">
            <div class="header-txt-wrap">
                <h1>Welcome</h1>
                <h3>This page is running on MVC framework</h3>
            </div>
        </div>
    </div>
    <div class="body-1-main">
        <div class="sec">
            <div class="sec-12">
                <div class="center-div">
                    <div class="sec">
                        <div class="sec-6">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                        </div>
                        <div class="sec-6">
                            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="body-2-main">
        <div class="sec">
            <div class="sec-12">
                <div class="center-div">
                    <div class="sec small-contents-wrap">
                        <div class="sec-3">
                            <div class="small-icon-content">
                                <i class="fi fi-rr-settings-sliders"></i>
                                <div>Sed ut perspiciatis unde omnis</div>
                            </div>
                        </div>
                        <div class="sec-3">
                            <div class="small-icon-content">
                                <i class="fi fi-rr-trophy"></i>
                                <div>Iste natus error sit voluptatem accusantium</div>
                            </div>
                        </div>
                        <div class="sec-3">
                            <div class="small-icon-content">
                                <i class="fi fi-rr-globe"></i>
                                <div>Doloremque laudantium, totam rem aperiam</div>
                            </div>
                        </div>
                        <div class="sec-3">
                            <div class="small-icon-content">
                                <i class="fi fi-rr-stats"></i>
                                <div>Eaque ipsa quae ab illo inventore</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="body-3-main">
        <div class="sec">
            <div class="sec-12">
                <div class="center-div">
                    <div class="sec">
                        <div class="sec-6">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                        </div>
                        <div class="sec-6">
                            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-main">
        <div class="footer-wrap">
            <div class="sec">
                <div class="sec-3">
                    <span class="footer-header">OVERVIEW</span>
                    <ul class="footer-list">
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Services</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Contact Us</a></li>
                    </ul>
                </div>
                <div class="sec-3">
                    <span class="footer-header">CONTACT US</span>
                    <ul class="footer-list">
                        <li><i class="fi fi-rr-envelope"></i> Test@test.com</li>
                        <li><i class="fi fi-rr-phone-call"></i> 0000-9871</li>
                    </ul>
                </div>
                <div class="sec-3">
                    <span class="footer-header">FOLLOW US</span>
                    <ul class="footer-list">
                        <li><i class="fi fi-brands-facebook"></i> Test</li>
                        <li><i class="fi fi-brands-instagram"></i> Test</li>
                        <li><i class="fi fi-brands-youtube"></i> Test</li>
                    </ul>
                </div>
                <div class="sec-3">
                    <span class="footer-header">OUR LOCATION</span>
                    <ul class="footer-list">
                        <li><i class="fi fi-rr-map-marker"></i> Bonifacio Global City</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php blockEnd() ?>


