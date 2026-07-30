<div class="main-nav">
    <!-- Sidebar Logo -->
    <div class="logo-box">
        <a href="/" class="logo-dark">
            <img
                src="/images/logo-sm.png"
                class="logo-sm"
                alt="logo sm"
            />
            <img
                src="/images/logo-dark.png"
                class="logo-lg"
                alt="logo dark"
            />
        </a>

        <a href="/" class="logo-light">
            <img
                src="/images/logo-sm.png"
                class="logo-sm"
                alt="logo sm"
            />
            <img
                src="/images/logo-light.png"
                class="logo-lg"
                alt="logo light"
            />
        </a>
    </div>

    <!-- Menu Toggle Button (sm-hover) -->
    <button
        type="button"
        class="button-sm-hover"
        aria-label="Show Full Sidebar"
    >
        <iconify-icon
            icon="iconamoon:arrow-left-4-square-duotone"
            class="button-sm-hover-icon"
        ></iconify-icon>
    </button>

    <div class="scrollbar" data-simplebar>
        <ul class="navbar-nav" id="navbar-nav">
            <li class="menu-title">General</li>

            <li class="nav-item">
                <a
                    class="nav-link menu-arrow"
                    href="#sidebarDashboards"
                    data-bs-toggle="collapse"
                    role="button"
                    aria-expanded="false"
                    aria-controls="sidebarDashboards"
                >
                    <span class="nav-icon">
                        <iconify-icon
                            icon="iconamoon:home-duotone"
                        ></iconify-icon>
                    </span>
                    <span class="nav-text"> Dashboards </span>
                </a>
                <div class="collapse" id="sidebarDashboards">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="/"
                                >Analytics</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="dashboard-finance"
                                >Finance</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="dashboard-sales"
                                >Sales</a
                            >
                        </li>
                    </ul>
                </div>
            </li>

            <li class="menu-title">Apps</li>

            <li class="nav-item">
                <a
                    class="nav-link menu-arrow"
                    href="#sidebarEcommerce"
                    data-bs-toggle="collapse"
                    role="button"
                    aria-expanded="false"
                    aria-controls="sidebarEcommerce"
                >
                    <span class="nav-icon">
                        <iconify-icon
                            icon="iconamoon:shopping-bag-duotone"
                        ></iconify-icon>
                    </span>
                    <span class="nav-text"> Ecommerce </span>
                </a>
                <div class="collapse" id="sidebarEcommerce">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="apps-ecommerce-product-list"
                                >Products</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="apps-ecommerce-product-detail"
                                >Product Details</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="apps-ecommerce-product-add"
                                >Create Product</a
                            >
                        </li>

                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="apps-ecommerce-customer-list"
                                >Customers</a
                            >
                        </li>

                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="apps-ecommerce-seller-list"
                                >Sellers</a
                            >
                        </li>

                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="apps-ecommerce-order-list"
                                >Orders</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="apps-ecommerce-order-detail"
                                >Order Details</a
                            >
                        </li>

                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="apps-ecommerce-inventory"
                                >Inventory</a
                            >
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="apps-chat">
                    <span class="nav-icon">
                        <iconify-icon
                            icon="iconamoon:comment-dots-duotone"
                        ></iconify-icon>
                    </span>
                    <span class="nav-text"> Chat </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="apps-email">
                    <span class="nav-icon">
                        <iconify-icon
                            icon="iconamoon:email-duotone"
                        ></iconify-icon>
                    </span>
                    <span class="nav-text"> Email </span>
                </a>
            </li>

            <li class="nav-item">
                <a
                    class="nav-link menu-arrow"
                    href="#sidebarCalendar"
                    data-bs-toggle="collapse"
                    role="button"
                    aria-expanded="false"
                    aria-controls="sidebarCalendar"
                >
                    <span class="nav-icon">
                        <iconify-icon
                            icon="iconamoon:calendar-1-duotone"
                        ></iconify-icon>
                    </span>
                    <span class="nav-text"> Calendar </span>
                </a>
                <div class="collapse" id="sidebarCalendar">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="apps-calendar-schedule"
                                >Schedule</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="apps-calendar-integration"
                                >Integration</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="apps-calendar-help"
                                >Help</a
                            >
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="apps-todo">
                    <span class="nav-icon">
                        <iconify-icon
                            icon="iconamoon:ticket-duotone"
                        ></iconify-icon>
                    </span>
                    <span class="nav-text"> Todo </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="apps-social">
                    <span class="nav-icon">
                        <iconify-icon
                            icon="iconamoon:squinting-face-duotone"
                        ></iconify-icon>
                    </span>
                    <span class="nav-text"> Social </span>
                    <span class="badge badge-pill text-end bg-danger">Hot</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="apps-contacts">
                    <span class="nav-icon">
                        <iconify-icon
                            icon="iconamoon:profile-circle-duotone"
                        ></iconify-icon>
                    </span>
                    <span class="nav-text"> Contacts </span>
                </a>
            </li>

            <li class="nav-item">
                <a
                    class="nav-link menu-arrow"
                    href="#sidebarInvoice"
                    data-bs-toggle="collapse"
                    role="button"
                    aria-expanded="false"
                    aria-controls="sidebarInvoice"
                >
                    <span class="nav-icon">
                        <iconify-icon
                            icon="iconamoon:invoice-duotone"
                        ></iconify-icon>
                    </span>
                    <span class="nav-text"> Invoices </span>
                </a>
                <div class="collapse" id="sidebarInvoice">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="apps-invoices"
                                >Invoices</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="apps-invoice-details"
                                >Invoice Details</a
                            >
                        </li>
                    </ul>
                </div>
            </li>

            <li class="menu-title">Custom</li>

            <li class="nav-item">
                <a
                    class="nav-link menu-arrow"
                    href="#sidebarPages"
                    data-bs-toggle="collapse"
                    role="button"
                    aria-expanded="false"
                    aria-controls="sidebarPages"
                >
                    <span class="nav-icon">
                        <iconify-icon
                            icon="iconamoon:copy-duotone"
                        ></iconify-icon>
                    </span>
                    <span class="nav-text"> Pages </span>
                </a>
                <div class="collapse" id="sidebarPages">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="pages-starter"
                                >Welcome</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="pages-faqs"
                                >FAQs</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="pages-profile"
                                >Profile</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="pages-comingsoon"
                                >Coming Soon</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="pages-contact-us"
                                >Contact Us</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="pages-about-us"
                                >About Us</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="pages-team"
                                >Our Team</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="pages-timeline"
                                >Timeline</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="pages-pricing"
                                >Pricing</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="pages-maintenance"
                                >Maintenance</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="pages-404"
                                >404 Error</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="pages-404-2"
                                >404 Error 2</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="pages-404-alt"
                                >404 Error (alt)</a
                            >
                        </li>
                    </ul>
                </div>
            </li>
            <!-- end Pages Menu -->

            <li class="nav-item">
                <a
                    class="nav-link menu-arrow"
                    href="#sidebarAuthentication"
                    data-bs-toggle="collapse"
                    role="button"
                    aria-expanded="false"
                    aria-controls="sidebarAuthentication"
                >
                    <span class="nav-icon">
                        <iconify-icon
                            icon="iconamoon:lock-duotone"
                        ></iconify-icon>
                    </span>
                    <span class="nav-text"> Authentication </span>
                </a>
                <div class="collapse" id="sidebarAuthentication">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="auth-signin"
                                >Sign In</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="auth-signin2"
                                >Sign In 2</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="auth-signup"
                                >Sign Up</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="auth-signup2"
                                >Sign Up 2</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="auth-password"
                                >Reset Password</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="auth-password2"
                                >Reset Password 2</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="auth-lock-screen"
                                >Lock Screen</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="auth-lock-screen2"
                                >Lock Screen 2</a
                            >
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="widgets">
                    <span class="nav-icon">
                        <iconify-icon
                            icon="iconamoon:gift-duotone"
                        ></iconify-icon>
                    </span>
                    <span class="nav-text">Widgets</span>
                    <span class="badge bg-info badge-pill text-end">9+</span>
                </a>
            </li>
            <!-- end Demo Menu Item -->

            <li class="menu-title">Components</li>

            <li class="nav-item">
                <a
                    class="nav-link menu-arrow"
                    href="#sidebarBaseUI"
                    data-bs-toggle="collapse"
                    role="button"
                    aria-expanded="false"
                    aria-controls="sidebarBaseUI"
                >
                    <span class="nav-icon"
                        ><iconify-icon
                            icon="iconamoon:briefcase-duotone"
                        ></iconify-icon
                    ></span>
                    <span class="nav-text"> Base UI </span>
                </a>
                <div class="collapse" id="sidebarBaseUI">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="ui-accordion"
                                >Accordion</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="ui-alerts"
                                >Alerts</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="ui-avatar"
                                >Avatar</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="ui-badge"
                                >Badge</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="ui-breadcrumb"
                                >Breadcrumb</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="ui-buttons"
                                >Buttons</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="ui-card">Card</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="ui-carousel"
                                >Carousel</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="ui-collapse"
                                >Collapse</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="ui-dropdown"
                                >Dropdown</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="ui-list-group"
                                >List Group</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="ui-modal"
                                >Modal</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="ui-tabs">Tabs</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="ui-offcanvas"
                                >Offcanvas</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="ui-pagination"
                                >Pagination</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="ui-placeholders"
                                >Placeholders</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="ui-popovers"
                                >Popovers</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="ui-progress"
                                >Progress</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="ui-scrollspy"
                                >Scrollspy</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="ui-spinners"
                                >Spinners</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="ui-toasts"
                                >Toasts</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="ui-tooltips"
                                >Tooltips</a
                            >
                        </li>
                    </ul>
                </div>
            </li>
            <!-- end Base UI Menu -->

            <li class="nav-item">
                <a
                    class="nav-link menu-arrow"
                    href="#sidebarExtendedUI"
                    data-bs-toggle="collapse"
                    role="button"
                    aria-expanded="false"
                    aria-controls="sidebarExtendedUI"
                >
                    <span class="nav-icon"
                        ><iconify-icon
                            icon="iconamoon:component-duotone"
                        ></iconify-icon
                    ></span>
                    <span class="nav-text"> Advanced UI </span>
                </a>
                <div class="collapse" id="sidebarExtendedUI">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="extended-ratings"
                                >Ratings</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="extended-sweetalert"
                                >Sweet Alert</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="extended-swiper-silder"
                                >Swiper Slider</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="extended-scrollbar"
                                >Scrollbar</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="extended-toastify"
                                >Toastify</a
                            >
                        </li>
                    </ul>
                </div>
            </li>
            <!-- end Extended UI Menu -->

            <li class="nav-item">
                <a
                    class="nav-link menu-arrow"
                    href="#sidebarCharts"
                    data-bs-toggle="collapse"
                    role="button"
                    aria-expanded="false"
                    aria-controls="sidebarCharts"
                >
                    <span class="nav-icon">
                        <iconify-icon
                            icon="iconamoon:3d-duotone"
                        ></iconify-icon>
                    </span>
                    <span class="nav-text"> Charts </span>
                </a>
                <div class="collapse" id="sidebarCharts">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="charts-apex-area"
                                >Area</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="charts-apex-bar"
                                >Bar</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="charts-apex-bubble"
                                >Bubble</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="charts-apex-candlestick"
                                >Candlestick</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="charts-apex-column"
                                >Column</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="charts-apex-heatmap"
                                >Heatmap</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="charts-apex-line"
                                >Line</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="charts-apex-mixed"
                                >Mixed</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="charts-apex-timeline"
                                >Timeline</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="charts-apex-boxplot"
                                >Boxplot</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="charts-apex-treemap"
                                >Treemap</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="charts-apex-pie"
                                >Pie</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="charts-apex-radar"
                                >Radar</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="charts-apex-radialbar"
                                >RadialBar</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="charts-apex-scatter"
                                >Scatter</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="charts-apex-polar-area"
                                >Polar Area</a
                            >
                        </li>
                    </ul>
                </div>
            </li>
            <!-- end Chart library Menu -->

            <li class="nav-item">
                <a
                    class="nav-link menu-arrow"
                    href="#sidebarForms"
                    data-bs-toggle="collapse"
                    role="button"
                    aria-expanded="false"
                    aria-controls="sidebarForms"
                >
                    <span class="nav-icon">
                        <iconify-icon
                            icon="iconamoon:cheque-duotone"
                        ></iconify-icon>
                    </span>
                    <span class="nav-text"> Forms </span>
                </a>
                <div class="collapse" id="sidebarForms">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="forms-basic"
                                >Basic Elements</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="forms-checkbox-radio"
                                >Checkbox &amp; Radio</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="forms-choices"
                                >Choice Select</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="forms-clipboard"
                                >Clipboard</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="forms-flatepicker"
                                >Flatepicker</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="forms-validation"
                                >Validation</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="forms-wizard"
                                >Wizard</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="forms-fileuploads"
                                >File Upload</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="forms-editors"
                                >Editors</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="forms-input-mask"
                                >Input Mask</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="forms-range-slider"
                                >Slider</a
                            >
                        </li>
                    </ul>
                </div>
            </li>
            <!-- end Form Menu -->

            <li class="nav-item">
                <a
                    class="nav-link menu-arrow"
                    href="#sidebarTables"
                    data-bs-toggle="collapse"
                    role="button"
                    aria-expanded="false"
                    aria-controls="sidebarTables"
                >
                    <span class="nav-icon">
                        <iconify-icon
                            icon="iconamoon:box-duotone"
                        ></iconify-icon>
                    </span>
                    <span class="nav-text"> Tables </span>
                </a>
                <div class="collapse" id="sidebarTables">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="tables-basic"
                                >Basic Tables</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="tables-gridjs"
                                >Grid Js</a
                            >
                        </li>
                    </ul>
                </div>
            </li>
            <!-- end Table Menu -->

            <li class="nav-item">
                <a
                    class="nav-link menu-arrow"
                    href="#sidebarIcons"
                    data-bs-toggle="collapse"
                    role="button"
                    aria-expanded="false"
                    aria-controls="sidebarIcons"
                >
                    <span class="nav-icon">
                        <iconify-icon
                            icon="iconamoon:lightning-1-duotone"
                        ></iconify-icon>
                    </span>
                    <span class="nav-text"> Icons </span>
                </a>
                <div class="collapse" id="sidebarIcons">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="icons-boxicons"
                                >Boxicons</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="icons-iconamoon"
                                >IconaMoon Icons</a
                            >
                        </li>
                    </ul>
                </div>
            </li>
            <!-- end Icons library Menu -->

            <li class="nav-item">
                <a
                    class="nav-link menu-arrow"
                    href="#sidebarMaps"
                    data-bs-toggle="collapse"
                    role="button"
                    aria-expanded="false"
                    aria-controls="sidebarMaps"
                >
                    <span class="nav-icon">
                        <iconify-icon
                            icon="iconamoon:location-pin-duotone"
                        ></iconify-icon>
                    </span>
                    <span class="nav-text"> Maps </span>
                </a>
                <div class="collapse" id="sidebarMaps">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="maps-google"
                                >Google Maps</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="maps-vector"
                                >Vector Maps</a
                            >
                        </li>
                    </ul>
                </div>
            </li>
            <!-- end Map Menu -->

            <li class="nav-item">
                <a class="nav-link" href="javascript:void(0);">
                    <span class="nav-icon">
                        <iconify-icon
                            icon="iconamoon:badge-duotone"
                        ></iconify-icon>
                    </span>
                    <span class="nav-text">Badge Menu</span>
                    <span class="badge bg-danger badge-pill text-end">1</span>
                </a>
            </li>
            <!-- end Demo Menu Item -->

            <li class="nav-item">
                <a
                    class="nav-link menu-arrow"
                    href="#sidebarMultiLevelDemo"
                    data-bs-toggle="collapse"
                    role="button"
                    aria-expanded="false"
                    aria-controls="sidebarMultiLevelDemo"
                >
                    <span class="nav-icon">
                        <iconify-icon
                            icon="iconamoon:folder-add-duotone"
                        ></iconify-icon>
                    </span>
                    <span class="nav-text"> Menu Item </span>
                </a>
                <div class="collapse" id="sidebarMultiLevelDemo">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="javascript:void(0);"
                                >Menu Item 1</a
                            >
                        </li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link menu-arrow"
                                href="#sidebarItemDemoSubItem"
                                data-bs-toggle="collapse"
                                role="button"
                                aria-expanded="false"
                                aria-controls="sidebarItemDemoSubItem"
                            >
                                <span> Menu Item 2 </span>
                            </a>
                            <div class="collapse" id="sidebarItemDemoSubItem">
                                <ul class="nav sub-navbar-nav">
                                    <li class="sub-nav-item">
                                        <a
                                            class="sub-nav-link"
                                            href="javascript:void(0);"
                                            >Menu Sub item</a
                                        >
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </li>
            <!-- end Demo Menu Item -->

            <li class="nav-item">
                <a class="nav-link disabled" href="javascript:void(0);">
                    <span class="nav-icon">
                        <iconify-icon
                            icon="iconamoon:unavailable-duotone"
                        ></iconify-icon>
                    </span>
                    <span class="nav-text"> Disabled Item </span>
                </a>
            </li>
            <!-- end Demo Menu Item -->
        </ul>
    </div>
</div>
