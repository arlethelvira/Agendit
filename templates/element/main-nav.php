<?php
$usuario = $this->request
    ->getSession()
    ->read('Usuario');

$rol = $usuario['rol'] ?? null;
?>

<div class="main-nav">

    <!-- =====================================================
         LOGO
    ====================================================== -->

    <div class="logo-box">

        <a href="/" class="logo-dark">

            <img
                src="/images/logoAgendit.png"
                class="logo-sm"
                alt="Agendit"
            />

            <img
                src="/images/letrasAgendit.png"
                class="logo-lg"
                alt="Agendit"
            />

        </a>


        <a href="/" class="logo-light">

            <img
                src="/images/logoAgendit.png"
                class="logo-sm"
                alt="Agendit"
            />

            <img
                src="/images/letrasAgendit.png"
                class="logo-lg"
                alt="Agendit"
            />

        </a>

    </div>


    <!-- =====================================================
         BOTÓN SIDEBAR
    ====================================================== -->

    <button
        type="button"
        class="button-sm-hover"
        aria-label="Mostrar menú completo"
    >

        <iconify-icon
            icon="iconamoon:arrow-left-4-square-duotone"
            class="button-sm-hover-icon"
        ></iconify-icon>

    </button>


    <!-- =====================================================
         MENÚ
    ====================================================== -->

    <div class="scrollbar" data-simplebar>

        <ul
            class="navbar-nav"
            id="navbar-nav"
        >


            <!-- =================================================
                 GENERAL
            ================================================== -->

            <li class="menu-title">
                General
            </li>


            <!-- DASHBOARD -->

            <li class="nav-item">

                <a
                    class="nav-link"
                    href="<?= $this->Url->build([
                        'controller' => 'Dashboard',
                        'action' => 'index'
                    ]) ?>"
                >

                    <span class="nav-icon">

                        <iconify-icon
                            icon="iconamoon:home-duotone"
                        ></iconify-icon>

                    </span>

                    <span class="nav-text">
                        Dashboard
                    </span>

                </a>

            </li>



            <!-- =================================================
                 USUARIO
            ================================================== -->

            <?php if ($rol === 'usuario'): ?>


                <!-- =============================================
                     ORGANIZACIÓN
                ============================================== -->

                <li class="menu-title">
                    Organización
                </li>


                <!-- TAREAS -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="<?= $this->Url->build('/tareas') ?>"
                    >

                        <span class="nav-icon">

                            <iconify-icon
                                icon="iconamoon:check-list-duotone"
                            ></iconify-icon>

                        </span>

                        <span class="nav-text">
                            Mis tareas
                        </span>

                    </a>

                </li>


                <!-- CALENDARIO DE TAREAS -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="<?= $this->Url->build([
                            'controller' => 'Tareas',
                            'action' => 'calendario'
                        ]) ?>"
                    >

                        <span class="nav-icon">

                            <iconify-icon
                                icon="iconamoon:calendar-1-duotone"
                            ></iconify-icon>

                        </span>

                        <span class="nav-text">
                            Calendario de tareas
                        </span>

                    </a>

                </li>


                <!-- HÁBITOS -->

                <li class="nav-item">

                  <a
    class="nav-link"
    href="<?= $this->Url->build('/habitos') ?>"
>

                        <span class="nav-icon">

                            <iconify-icon
                                icon="iconamoon:repeat-duotone"
                            ></iconify-icon>

                        </span>

                        <span class="nav-text">
                            Mis hábitos
                        </span>

                    </a>

                </li>


                <!-- CALENDARIO DE HÁBITOS -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="<?= $this->Url->build([
                            'controller' => 'Habitos',
                            'action' => 'calendario'
                        ]) ?>"
                    >

                        <span class="nav-icon">

                            <iconify-icon
                                icon="iconamoon:calendar-check-duotone"
                            ></iconify-icon>

                        </span>

                        <span class="nav-text">
                            Calendario de hábitos
                        </span>

                    </a>

                </li>



                <!-- =============================================
                     ESPECIALISTA
                ============================================== -->

                <li class="menu-title">
                    Especialista
                </li>


                <!-- VINCULAR ESPECIALISTA -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="<?= $this->Url->build([
                            'controller' => 'Vinculaciones',
                            'action' => 'ingresarCodigo'
                        ]) ?>"
                    >

                        <span class="nav-icon">

                            <iconify-icon
                                icon="iconamoon:link-duotone"
                            ></iconify-icon>

                        </span>

                        <span class="nav-text">
                            Vincular especialista
                        </span>

                    </a>

                </li>


            <?php endif; ?>



            <!-- =================================================
                 ESPECIALISTA
            ================================================== -->

            <?php if ($rol === 'especialista'): ?>


                <li class="menu-title">
                    Socios
                </li>


                <!-- MIS SOCIOS -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="<?= $this->Url->build([
                            'controller' => 'Vinculaciones',
                            'action' => 'misSocios'
                        ]) ?>"
                    >

                        <span class="nav-icon">

                            <iconify-icon
                                icon="iconamoon:profile-circle-duotone"
                            ></iconify-icon>

                        </span>

                        <span class="nav-text">
                            Mis socios
                        </span>

                    </a>

                </li>


                <!-- GENERAR CÓDIGO -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="<?= $this->Url->build([
                            'controller' => 'Vinculaciones',
                            'action' => 'generarCodigo'
                        ]) ?>"
                    >

                        <span class="nav-icon">

                            <iconify-icon
                                icon="iconamoon:copy-duotone"
                            ></iconify-icon>

                        </span>

                        <span class="nav-text">
                            Generar código
                        </span>

                    </a>

                </li>


            <?php endif; ?>



            <!-- =================================================
                 ADMIN
            ================================================== -->

            <?php if ($rol === 'admin'): ?>


                <li class="menu-title">
                    Administración
                </li>


                <!-- ESPECIALISTAS -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="<?= $this->Url->build([
                            'controller' => 'Admin',
                            'action' => 'index'
                        ]) ?>"
                    >

                        <span class="nav-icon">

                            <iconify-icon
                                icon="iconamoon:profile-circle-duotone"
                            ></iconify-icon>

                        </span>

                        <span class="nav-text">
                            Especialistas
                        </span>

                    </a>

                </li>


            <?php endif; ?>


        </ul>

    </div>

</div>