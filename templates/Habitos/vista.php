<?php
$this->assign('title', 'Mis Hábitos');
?>

<div class="container-xxl">

    <?= $this->element('page-title', [
        'title' => 'Mis Hábitos',
        'subTitle' => 'Agendit'
    ]) ?>


    <!-- ==========================================
         ENCABEZADO
    =========================================== -->

    <div class="row mb-4">

        <div class="col-12">

            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">

                    <div
                        class="
                            d-flex
                            flex-column
                            flex-md-row
                            justify-content-between
                            align-items-md-center
                            gap-3
                        "
                    >

                        <div>

                            <h3 class="fw-semibold mb-1">
                                Construye tu rutina 🌱
                            </h3>

                            <p class="text-muted mb-0">
                                Crea y organiza los hábitos que quieres mantener.
                            </p>

                        </div>


                        <div class="d-flex gap-2">

                            <a
                                href="<?= $this->Url->build([
                                    'controller' => 'Habitos',
                                    'action' => 'calendario'
                                ]) ?>"
                                class="btn btn-outline-success"
                            >
                                <i class="ti ti-calendar me-1"></i>

                                Calendario
                            </a>


                            <button
                                type="button"
                                id="btnNuevoHabito"
                                class="btn btn-success px-4"
                            >
                                <i class="ti ti-plus me-1"></i>

                                Nuevo hábito
                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ==========================================
         LISTADO
    =========================================== -->

    <div class="row">

        <div class="col-12">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-transparent border-0 pt-4 px-4">

                    <h4 class="mb-1">

                        <i class="ti ti-repeat text-success me-2"></i>

                        Mis hábitos

                    </h4>

                    <p class="text-muted mb-0">
                        Hábitos que actualmente forman parte de tu rutina.
                    </p>

                </div>


                <div class="card-body p-4">

                    <!--
                        JavaScript insertará aquí
                        las tarjetas.
                    -->
                    <div
                        id="listaHabitos"
                        class="lista-habitos-agendit"
                    ></div>

                </div>

            </div>

        </div>

    </div>

</div>



<!-- =========================================================
     MODAL CREAR / EDITAR
========================================================= -->

<div
    id="modalHabito"
    class="modal-overlay-agendit"
    style="display:none;"
>

    <div class="modal-habito-agendit">

        <div class="modal-header-agendit">

            <div>

                <span
                    class="
                        badge
                        bg-success-subtle
                        text-success
                        mb-2
                    "
                >
                    Agendit
                </span>

                <h4
                    id="modalHabitoTitulo"
                    class="mb-0"
                >
                    Nuevo hábito
                </h4>

            </div>


            <button
                type="button"
                class="btn-close"
                onclick="cerrarModalHabito()"
            ></button>

        </div>


        <form id="formHabito">

            <input
                type="hidden"
                id="idHabito"
            >


            <div class="modal-body-agendit">


                <!-- TÍTULO -->

                <div class="mb-3">

                    <label
                        for="habitoTitulo"
                        class="form-label fw-medium"
                    >
                        Título
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        id="habitoTitulo"
                        class="form-control"
                        maxlength="30"
                        placeholder="Ej. Tomar agua"
                        required
                    >

                </div>


                <!-- FRECUENCIA -->

                <div class="mb-3">

                    <label
                        for="habitoFrecuencia"
                        class="form-label fw-medium"
                    >
                        Frecuencia
                    </label>

                    <select
                        id="habitoFrecuencia"
                        class="form-select"
                        required
                    >

                        <option value="">
                            Selecciona una frecuencia
                        </option>

                        <option value="diaria">
                            Diaria
                        </option>

                        <option value="cada 2 dias">
                            Cada 2 días
                        </option>

                        <option value="cada 3 dias">
                            Cada 3 días
                        </option>

                        <option value="semanal">
                            Semanal
                        </option>

                        <option value="mensual">
                            Mensual
                        </option>

                    </select>

                </div>


                <!-- COLOR -->

                <div class="mb-3">

                    <label
                        for="habitoColor"
                        class="form-label fw-medium"
                    >
                        Color
                    </label>

                    <select
                        id="habitoColor"
                        class="form-select"
                        required
                    >

                        <option value="bg-success">
                            Verde
                        </option>

                        <option value="bg-primary">
                            Azul
                        </option>

                        <option value="bg-warning">
                            Amarillo
                        </option>

                        <option value="bg-danger">
                            Rojo
                        </option>

                        <option value="bg-purple">
                            Morado
                        </option>

                    </select>

                </div>


                <!-- NOTAS -->

                <div class="mb-0">

                    <label
                        for="habitoNotas"
                        class="form-label fw-medium"
                    >
                        Notas
                    </label>

                    <textarea
                        id="habitoNotas"
                        class="form-control"
                        maxlength="60"
                        rows="3"
                        placeholder="Agrega una pequeña descripción..."
                    ></textarea>

                    <div class="form-text">
                        Máximo 60 caracteres.
                    </div>

                </div>

            </div>


            <div class="modal-footer-agendit">

                <button
                    type="button"
                    class="btn btn-light"
                    onclick="cerrarModalHabito()"
                >
                    Cancelar
                </button>


                <button
                    type="submit"
                    class="btn btn-success px-4"
                >
                    <i class="ti ti-device-floppy me-1"></i>

                    Guardar
                </button>

            </div>

        </form>

    </div>

</div>



<!-- =========================================================
     MODAL ELIMINAR
========================================================= -->

<div
    id="modalEliminarHabito"
    class="modal-overlay-agendit"
    style="display:none;"
>

    <div class="modal-confirmacion-agendit">

        <div class="text-center p-4">

            <div
                class="
                    icono-eliminar-agendit
                    mx-auto
                    mb-3
                "
            >

                <i class="ti ti-trash fs-2"></i>

            </div>


            <h4 class="mb-2">
                ¿Eliminar hábito?
            </h4>


            <p class="text-muted mb-4">
                Esta acción no se puede deshacer.
            </p>


            <div class="d-flex justify-content-center gap-2">

                <button
                    type="button"
                    class="btn btn-light px-4"
                    onclick="cerrarModalEliminarHabito()"
                >
                    Cancelar
                </button>


                <button
                    type="button"
                    id="btnConfirmarEliminarHabito"
                    class="btn btn-danger px-4"
                >

                    <i class="ti ti-trash me-1"></i>

                    Eliminar

                </button>

            </div>

        </div>

    </div>

</div>



<style>

    /* ==========================================
       LISTA
    ========================================== */

    .lista-habitos-agendit {
        display: grid;

        grid-template-columns:
            repeat(auto-fill, minmax(300px, 1fr));

        gap: 16px;
    }


    .tarjeta-habito {
        border-radius: 14px;

        transition:
            transform .2s ease,
            box-shadow .2s ease;
    }


    .tarjeta-habito:hover {
        transform: translateY(-3px);

        box-shadow:
            0 8px 24px rgba(0, 0, 0, .09) !important;
    }


    /* ==========================================
       INDICADOR
    ========================================== */

    .habito-indicador {
        width: 12px;
        height: 12px;

        border-radius: 50%;

        flex-shrink: 0;
    }


    /* ==========================================
       MODALES
    ========================================== */

    .modal-overlay-agendit {
        position: fixed;
        inset: 0;

        background: rgba(0, 0, 0, .55);

        display: flex;
        align-items: center;
        justify-content: center;

        padding: 20px;

        z-index: 9999;
    }


    .modal-habito-agendit,
    .modal-confirmacion-agendit {
        width: 100%;

        background:
            var(--bs-body-bg, #ffffff);

        border-radius: 18px;

        box-shadow:
            0 20px 60px rgba(0, 0, 0, .25);

        animation: aparecerModal .2s ease;
    }


    .modal-habito-agendit {
        max-width: 580px;
    }


    .modal-confirmacion-agendit {
        max-width: 420px;
    }


    .modal-header-agendit {
        padding: 22px 24px;

        display: flex;
        align-items: flex-start;
        justify-content: space-between;

        border-bottom:
            1px solid rgba(0, 0, 0, .08);
    }


    .modal-body-agendit {
        padding: 24px;
    }


    .modal-footer-agendit {
        padding: 16px 24px;

        display: flex;
        justify-content: flex-end;

        gap: 10px;

        border-top:
            1px solid rgba(0, 0, 0, .08);
    }


    /* ==========================================
       ELIMINAR
    ========================================== */

    .icono-eliminar-agendit {
        width: 70px;
        height: 70px;

        display: flex;
        justify-content: center;
        align-items: center;

        border-radius: 50%;

        background:
            rgba(220, 53, 69, .12);

        color:
            #dc3545;
    }


    /* ==========================================
       ANIMACIÓN
    ========================================== */

    @keyframes aparecerModal {

        from {
            opacity: 0;
            transform:
                translateY(10px)
                scale(.98);
        }

        to {
            opacity: 1;
            transform:
                translateY(0)
                scale(1);
        }

    }


    /* ==========================================
       DARK MODE
    ========================================== */

    [data-bs-theme="dark"]
    .modal-habito-agendit,

    [data-bs-theme="dark"]
    .modal-confirmacion-agendit {
        background: #20262d;
    }


    /* ==========================================
       RESPONSIVE
    ========================================== */

    @media (max-width: 576px) {

        .lista-habitos-agendit {
            grid-template-columns: 1fr;
        }

        .modal-overlay-agendit {
            padding: 10px;
        }

    }

</style>


<?= $this->Html->script('habitos/habitos_funciones') ?>