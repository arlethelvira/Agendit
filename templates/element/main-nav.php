<?php
$usuario = $this->request->getSession()->read('Usuario');
$rol = $usuario['rol'] ?? null;
?>
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
                                >principal</a
                            >
                        </li>
                    </ul>
                </div>
            </li>
<?php if ($rol === 'especialista'): ?>
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
                                >Progresossssss o algo</a
                            >
                        </li>
                       
                        </li>


                    </ul>
                </div>
            </li>
            <?php endif; ?>
<?php if ($rol === 'admin'): ?>
            <li class="menu-title">Especialistas</li>

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
                    <span class="nav-text"> Especialistas </span>
                </a>
                <div class="collapse" id="sidebarEcommerce">
                    <ul class="nav sub-navbar-nav">
                <li class="sub-nav-item">
                   <a
                   class="sub-nav-link"
                       href="<?= $this->Url->build([
                       'controller' => 'Admin',
                        'action' => 'index'
                        ]) ?>"
                                >
                        Mis especialistas
                          </a>
                           </li>

                       
                        </li>


                    </ul>
                </div>
            </li>
            <?php endif; ?>


<?php if ($rol === 'usuario'): ?>
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
    <?php endif; ?>

            <li class="nav-item">
                <a class="nav-link" href="apps-todo">
                    <span class="nav-icon">
                        <iconify-icon
                            icon="iconamoon:ticket-duotone"
                        ></iconify-icon>
                    </span>
                    <span class="nav-text"> Todo-dejo este para meli </span>
                </a>
            </li>





 <?php if ($rol === 'especialista'): ?>

<li class="menu-title">Invitación</li>

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

        <span class="nav-text"> Invitación </span>
    </a>

    <div class="collapse" id="sidebarPages">
        <ul class="nav sub-navbar-nav">

            <li class="sub-nav-item">
                <a
                    class="sub-nav-link"
                    href="<?= $this->Url->build([
                        'controller' => 'Vinculaciones',
                        'action' => 'generarCodigo'
                    ]) ?>"
                >
                    Generar código
                </a>
            </li>

        </ul>
    </div>
 </li>

<?php endif; ?>
            <!-- end Pages Menu -->

<?php if ($rol === 'usuario'): ?>

<li class="menu-title">Invitación</li>

<li class="nav-item">
    <a
        class="nav-link menu-arrow"
        href="#sidebarPagesUsuario"
        data-bs-toggle="collapse"
        role="button"
        aria-expanded="false"
        aria-controls="sidebarPagesUsuario"
    >
        <span class="nav-icon">
            <iconify-icon
                icon="iconamoon:copy-duotone"
            ></iconify-icon>
        </span>

        <span class="nav-text"> Invitación </span>
    </a>

    <div class="collapse" id="sidebarPagesUsuario">
        <ul class="nav sub-navbar-nav">

            <li class="sub-nav-item">
                <a
                    class="sub-nav-link"
                    href="<?= $this->Url->build([
                        'controller' => 'Vinculaciones',
                        'action' => 'ingresarCodigo'
                    ]) ?>"
                >
                    Vincular especialista
                </a>
            </li>

        </ul>
    </div>
</li>

<?php endif; ?>




            </li>
            <!-- end Demo Menu Item -->
        </ul>
    </div>
</div>