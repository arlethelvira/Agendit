<header class="topbar">
    <div class="container-xxl">
        <div class="navbar-header">
            <div class="d-flex align-items-center gap-2">
                <!-- Menu Toggle Button -->
                <div class="topbar-item">
                    <button type="button" class="button-toggle-menu">
                        <iconify-icon
                            icon="iconamoon:menu-burger-horizontal"
                            class="fs-22"
                        ></iconify-icon>
                    </button>
                </div>

                <!-- App Search-->
                <form class="app-search d-none d-md-block me-auto">
                    <div class="position-relative">
                        <input
                            type="search"
                            class="form-control"
                            placeholder="Search..."
                            autocomplete="off"
                            value=""
                        />
                        <iconify-icon
                            icon="iconamoon:search-duotone"
                            class="search-widget-icon"
                        ></iconify-icon>
                    </div>
                </form>
            </div>

            <div class="d-flex align-items-center gap-1">
                <!-- Theme Color (Light/Dark) -->
                <div class="topbar-item">
                    <button
                        type="button"
                        class="topbar-button"
                        id="light-dark-mode"
                    >
                        <iconify-icon
                            icon="iconamoon:mode-dark-duotone"
                            class="fs-24 align-middle"
                        ></iconify-icon>
                    </button>
                </div>

                <!-- Category -->
                <div class="dropdown topbar-item d-none d-lg-flex">
                    <button
                        type="button"
                        class="topbar-button"
                        data-bs-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <iconify-icon
                            icon="iconamoon:apps"
                            class="fs-24 align-middle"
                        ></iconify-icon>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end p-0">
                        <div class="p-1">
                            <a
                                class="dropdown-item py-2"
                                href="javascript:void(0);"
                            >
                                <img
                                    src="/images/brands/github.svg"
                                    class="avatar-xs"
                                    alt="Github"
                                />
                                <span class="ms-2"
                                    >GitHub:
                                    <span class="fw-medium">@reback</span></span
                                >
                            </a>
                            <a
                                class="dropdown-item py-2"
                                href="javascript:void(0);"
                            >
                                <img
                                    src="/images/brands/bitbucket.svg"
                                    class="avatar-xs"
                                    alt="bitbucket"
                                />
                                <span class="ms-2"
                                    >Bitbucket:
                                    <span class="fw-medium">@reback</span></span
                                >
                            </a>
                            <a
                                class="dropdown-item py-2"
                                href="javascript:void(0);"
                            >
                                <img
                                    src="/images/brands/dribbble.svg"
                                    class="avatar-xs"
                                    alt="dribbble"
                                />
                                <span class="ms-2"
                                    >Dribbble:
                                    <span class="fw-medium"
                                        >@username</span
                                    ></span
                                >
                            </a>

                            <a
                                class="dropdown-item py-2"
                                href="javascript:void(0);"
                            >
                                <img
                                    src="/images/brands/dropbox.svg"
                                    class="avatar-xs"
                                    alt="dropbox"
                                />
                                <span class="ms-2"
                                    >Dropbox:
                                    <span class="fw-medium"
                                        >@username</span
                                    ></span
                                >
                            </a>

                            <a
                                class="dropdown-item py-2"
                                href="javascript:void(0);"
                            >
                                <img
                                    src="/images/brands/slack.svg"
                                    class="avatar-xs"
                                    alt="mail_chimp"
                                />
                                <span class="ms-2"
                                    >Slack:
                                    <span class="fw-medium">@reback</span></span
                                >
                            </a>
                        </div>
                    </div>
                </div>



                <!-- Theme Setting -->
                <div class="topbar-item">
                    <button
                        type="button"
                        class="topbar-button"
                        id="theme-settings-btn"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#theme-settings-offcanvas"
                        aria-controls="theme-settings-offcanvas"
                    >
                        <iconify-icon
                            icon="iconamoon:settings-duotone"
                            class="fs-24 align-middle"
                        ></iconify-icon>
                    </button>
                </div>



                <!-- User -->
<!-- User -->
<?php
$usuarioSesion = $this->request->getSession()->read('Usuario');

$nombreUsuario = $usuarioSesion['nombre'] ?? 'Usuario';
$rolUsuario = $usuarioSesion['rol'] ?? 'usuario';

/*
 * Obtenemos la primera letra del nombre
 * para mostrarla como avatar.
 */
$inicial = strtoupper(mb_substr($nombreUsuario, 0, 1));
?>

<div class="dropdown topbar-item">

    <!-- Botón del usuario -->
    <a
        type="button"
        class="topbar-button"
        id="page-header-user-dropdown"
        data-bs-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false"
    >

        <span class="d-flex align-items-center">

            <!-- Avatar con inicial -->
            <span
                class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                style="
                    width: 32px;
                    height: 32px;
                    background: #6f42c1;
                    font-size: 15px;
                "
            >
                <?= h($inicial) ?>
            </span>

        </span>

    </a>


    <!-- Menú desplegable -->
    <div class="dropdown-menu dropdown-menu-end shadow">

        <!-- Información del usuario -->
        <div class="px-3 py-3">

            <div class="d-flex align-items-center gap-2">

                <!-- Avatar grande -->
                <span
                    class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                    style="
                        width: 42px;
                        height: 42px;
                        background: #6f42c1;
                        font-size: 18px;
                    "
                >
                    <?= h($inicial) ?>
                </span>

                <div>

                    <!-- Nombre -->
                    <h6 class="mb-0 fw-semibold">
                        <?= h($nombreUsuario) ?>
                    </h6>

                    <!-- Rol -->
                    <small class="text-muted">
                        <?= h(ucfirst($rolUsuario)) ?>
                    </small>

                </div>

            </div>

        </div>


        <div class="dropdown-divider my-1"></div>


        <!-- Cerrar sesión -->
        <a
            class="dropdown-item text-danger py-2"
            href="<?= $this->Url->build([
                'controller' => 'Users',
                'action' => 'logout'
            ]) ?>"
        >

            <i class="bx bx-log-out fs-18 align-middle me-2"></i>

            <span class="align-middle">
                Cerrar sesión
            </span>

        </a>

    </div>

</div>
            </div>
        </div>
    </div>
</header>




<!-- Right Sidebar (Theme Settings) -->
<?= $this->element('right-sidebar') ?>
