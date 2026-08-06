<div class="main-nav">
    <!-- Sidebar Logo -->
    <div class="logo-box">
        <a href="/" class="logo-dark">
            <img
                src="/images/logoAgendit.png"
                class="logo-sm"
                alt="logo sm"
            />
            <img
                src="/images/letrasAgendit.png"
                class="logo-lg"
                alt="logo dark"
            />
        </a>

        <a href="/" class="logo-light">
            <img
                src="/images/logoAgendit.png"
                class="logo-sm"
                alt="logo sm"
            />
            <img
                src="/images/letrasAgendit.png"
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

            <li class="menu-title">Socios</li>

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
                            icon="iconamoon:profile-circle-duotone"
                        ></iconify-icon>
                    </span>
                    <span class="nav-text"> Socios </span>
                </a>
                <div class="collapse" id="sidebarEcommerce">
                    <ul class="nav sub-navbar-nav">
<li class="sub-nav-item">
    <a
        class="sub-nav-link"
        href="<?= $this->Url->build([
            'controller' => 'Vinculaciones',
            'action' => 'misSocios'
        ]) ?>"
    >
        Mis socios
    </a>
</li>
                        <li class="sub-nav-item">
                            <a
                                class="sub-nav-link"
                                href="apps-ecommerce-product-detail"
                                >Product Details</a
                            >
                        </li>
                       
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
                                href="<?= $this->Url->build([
                                    'controller' => 'Habitos',
                                    'action' => 'calendario'
                                ]) ?>"
                                >Calendario de Hábitos</a
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

            <li class="menu-title">Invitacion</li>

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
                    <span class="nav-text"> Invitacion </span>
                </a>
                <div class="collapse" id="sidebarPages">
                    <ul class="nav sub-navbar-nav">
                       <li class="sub-nav-item">
                        <a
                         class="sub-nav-link"
                         href="<?= $this->Url->build([
                          'controller' => 'Vinculaciones',
                         'action' => 'generarCodigo'
                         ]) ?>"> Generar codigo </a>
                         </li>
                        
                    </ul>
                    <ul class="nav sub-navbar-nav">
                       <li class="sub-nav-item">
                        <a
                         class="sub-nav-link"
                         href="<?= $this->Url->build([
                          'controller' => 'Vinculaciones',
                         'action' => 'validarCodigo'
                         ]) ?>"> Vincular especialista </a>
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

     


            <!-- end Base UI Menu -->


            <!-- end Extended UI Menu -->


            <!-- end Chart library Menu -->



            <!-- end Table Menu -->




            <!-- end Demo Menu Item -->


            <!-- end Demo Menu Item -->

            </li>
            <!-- end Demo Menu Item -->
        </ul>
    </div>
</div>