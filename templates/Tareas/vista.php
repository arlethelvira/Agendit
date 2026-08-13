<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Collection\CollectionInterface|\App\Model\Entity\Categoria[] $categorias
 */

$this->assign('title', 'Mis Tareas');
?>

<div class="container-xxl">

    <?= $this->element('page-title', [
        'title' => 'Mis Tareas',
        'subTitle' => 'Agendit'
    ]) ?>


    <!-- ==========================================
         ENCABEZADO
    =========================================== -->
    <div class="row mb-4">

        <div class="col-12">

            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">

                    <div class="d-flex flex-column flex-md-row
                                justify-content-between align-items-md-center gap-3">

                        <div>

                            <h3 class="mb-1 fw-semibold">
                                Organiza tus tareas
                            </h3>

                            <p class="text-muted mb-0">
                                Consulta, crea y administra tus pendientes.
                            </p>

                        </div>


                        <!--
                            IMPORTANTE:
                            Conservamos el ID porque el JS
                            utiliza este botón.
                        -->
                        <button
                            type="button"
                            id="btnNuevaTarea"
                            class="btn btn-success px-4"
                        >
                            <i class="ti ti-plus me-1"></i>
                            Nueva tarea
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ==========================================
         LISTA DE TAREAS
    =========================================== -->
    <div class="row">

        <div class="col-12">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-transparent border-0 pt-4 px-4">

                    <div class="d-flex align-items-center justify-content-between">

                        <div>

                            <h4 class="mb-1">
                                <i class="ti ti-checklist text-success me-2"></i>
                                Mis tareas
                            </h4>

                            <p class="text-muted mb-0">
                                Tus actividades pendientes y próximas entregas.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="card-body p-4">

                    <!--
                        NO CAMBIAR ESTE ID.
                        Aquí tarea_funciones.js inserta las tareas.
                    -->
                    <div id="listaTareas" class="lista-tareas-agendit"></div>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     MODAL NUEVA / EDITAR TAREA
========================================================= -->

<div
    id="modalTarea"
    class="modal-overlay-agendit"
    style="display:none;"
>

    <div class="modal-tarea-agendit">

        <!-- Encabezado -->
        <div class="modal-header-agendit">

            <div>

                <span class="badge bg-success-subtle text-success mb-2">
                    Agendit
                </span>

                <h4 id="modalTitulo" class="mb-0">
                    Nueva tarea
                </h4>

            </div>


            <button
                type="button"
                class="btn-close"
                onclick="cerrarModal()"
                aria-label="Cerrar"
            ></button>

        </div>


        <!-- Formulario -->
        <form id="formTarea">

            <input
                type="hidden"
                id="idTarea"
                name="id_tarea"
            >


            <div class="modal-body-agendit">


                <!-- TÍTULO -->
                <div class="mb-3">

                    <label
                        for="titulo"
                        class="form-label fw-medium"
                    >
                        Título
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        id="titulo"
                        name="titulo"
                        class="form-control"
                        maxlength="30"
                        placeholder="Ej. Terminar proyecto"
                        required
                    >

                </div>


                <!-- FECHA Y HORA -->
                <div class="row g-3">

                    <div class="col-md-6">

                        <label
                            for="fechaLimite"
                            class="form-label fw-medium"
                        >
                            Fecha límite
                        </label>

                        <input
                            type="date"
                            id="fechaLimite"
                            name="fecha_limite"
                            class="form-control"
                        >

                    </div>


                    <div class="col-md-6">

                        <label
                            for="horaLimite"
                            class="form-label fw-medium"
                        >
                            Hora límite
                        </label>

                        <input
                            type="time"
                            id="horaLimite"
                            name="hora_limite"
                            class="form-control"
                        >

                    </div>

                </div>


                <!-- CATEGORÍA -->
                <div class="mt-3 mb-3">

                    <label
                        for="idCategoria"
                        class="form-label fw-medium"
                    >
                        Categoría
                    </label>

                    <select
                        id="idCategoria"
                        name="id_categoria"
                        class="form-select"
                    >

                        <option value="">
                            Sin categoría
                        </option>

                        <?php foreach ($categorias as $cat): ?>

                            <option value="<?= $cat->id_categoria ?>">

                                <?= h($cat->nombre) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <!-- RECORDATORIO -->
                <div class="mb-3">

                    <label
                        for="horaRecordatorio"
                        class="form-label fw-medium"
                    >
                        Hora de recordatorio
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="ti ti-bell"></i>
                        </span>

                        <input
                            type="time"
                            id="horaRecordatorio"
                            name="hora_recordatorio"
                            class="form-control"
                        >

                    </div>

                    <small class="text-muted">
                        Opcional
                    </small>

                </div>


                <!-- NOTAS -->
                <div class="mb-3">

                    <label
                        for="notas"
                        class="form-label fw-medium"
                    >
                        Notas
                    </label>

                    <textarea
                        id="notas"
                        name="notas"
                        class="form-control"
                        maxlength="60"
                        rows="3"
                        placeholder="Agrega información adicional..."
                    ></textarea>

                    <div class="form-text">
                        Máximo 60 caracteres.
                    </div>

                </div>


                <!-- SUBTAREAS -->
                <div class="mb-2">

                    <div class="d-flex justify-content-between
                                align-items-center mb-2">

                        <label class="form-label fw-medium mb-0">
                            Subtareas
                        </label>


                        <button
                            type="button"
                            class="btn btn-sm btn-outline-success"
                            onclick="agregarCampoSubtarea()"
                        >
                            <i class="ti ti-plus me-1"></i>
                            Agregar
                        </button>

                    </div>


                    <!--
                        Conservamos el ID utilizado
                        por tarea_funciones.js
                    -->
                    <div id="listaSubtareasForm"></div>

                </div>

            </div>


            <!-- BOTONES -->
            <div class="modal-footer-agendit">

                <button
                    type="button"
                    class="btn btn-light"
                    onclick="cerrarModal()"
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
     MODAL CONFIRMAR ELIMINACIÓN
========================================================= -->

<div
    id="modalEliminarTarea"
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
                ¿Eliminar tarea?
            </h4>


            <p class="text-muted mb-4">
                Esta acción no se puede deshacer.
            </p>


            <div
                class="
                    d-flex
                    justify-content-center
                    gap-2
                "
            >

                <button
                    type="button"
                    class="btn btn-light px-4"
                    onclick="cerrarModalEliminar()"
                >
                    Cancelar
                </button>


                <button
                    type="button"
                    id="btnConfirmarEliminar"
                    class="btn btn-danger px-4"
                >
                    <i class="ti ti-trash me-1"></i>
                    Eliminar
                </button>

            </div>

        </div>

    </div>

</div>
<!-- =========================================================
     ESTILOS DE LA VISTA
========================================================= -->

<style>
    /* ==========================================
   MODAL CONFIRMAR ELIMINACIÓN
========================================== */

.modal-confirmacion-agendit {
    width: 100%;
    max-width: 420px;

    background: var(--bs-body-bg, #ffffff);

    border-radius: 18px;

    box-shadow:
        0 20px 60px rgba(0, 0, 0, .25);

    animation: aparecerModal .2s ease;
}


.icono-eliminar-agendit {
    width: 70px;
    height: 70px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;

    background: rgba(220, 53, 69, .12);

    color: #dc3545;
}


[data-bs-theme="dark"] .modal-confirmacion-agendit {
    background: #20262d;
}

    /* ==========================================
       LISTA DE TAREAS
    ========================================== */

    .lista-tareas-agendit {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }


    /*
     * Damos un poco de espacio a cualquier
     * elemento generado dinámicamente por JS.
     */
    #listaTareas > div {
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        padding: 16px;
        transition: all .2s ease;
    }


    #listaTareas > div:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 18px rgba(0, 0, 0, 0.08);
    }


    #listaTareas button {
        border-radius: 7px;
        padding: 5px 12px;
        margin-right: 5px;
    }


    #listaTareas input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #198754;
        cursor: pointer;
    }


    /* ==========================================
       MODAL
    ========================================== */

    .modal-overlay-agendit {
        position: fixed;
        inset: 0;

        background: rgba(0, 0, 0, 0.55);

        display: flex;
        align-items: center;
        justify-content: center;

        padding: 20px;

        z-index: 9999;
    }


    .modal-tarea-agendit {
        width: 100%;
        max-width: 620px;

        max-height: 90vh;
        overflow-y: auto;

        background: var(--bs-body-bg, #ffffff);

        border-radius: 16px;

        box-shadow:
            0 20px 60px rgba(0, 0, 0, 0.22);

        animation: aparecerModal .2s ease;
    }


    .modal-header-agendit {
        padding: 22px 24px;

        display: flex;
        justify-content: space-between;
        align-items: flex-start;

        border-bottom:
            1px solid rgba(0, 0, 0, 0.08);
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
            1px solid rgba(0, 0, 0, 0.08);
    }


    @keyframes aparecerModal {

        from {
            opacity: 0;
            transform: translateY(10px) scale(.98);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

    }


    /* ==========================================
       DARK MODE DE TU PLANTILLA
    ========================================== */

    [data-bs-theme="dark"] .modal-tarea-agendit {
        background: #20262d;
    }


    [data-bs-theme="dark"] #listaTareas > div {
        border-color: rgba(255,255,255,.08);
    }


    /* ==========================================
       RESPONSIVE
    ========================================== */

    @media (max-width: 576px) {

        .modal-overlay-agendit {
            padding: 10px;
        }

        .modal-tarea-agendit {
            max-height: 95vh;
        }

        .modal-header-agendit,
        .modal-body-agendit,
        .modal-footer-agendit {
            padding-left: 18px;
            padding-right: 18px;
        }

    }

    /* ==========================================
   TARJETAS DE TAREAS
========================================== */

.tarjeta-tarea {
    overflow: hidden;
    transition:
        transform .2s ease,
        box-shadow .2s ease;
}

.tarjeta-tarea:hover {
    transform: translateY(-2px);
    box-shadow: 0 7px 22px rgba(0, 0, 0, .09) !important;
}

.tarjeta-tarea.completada {
    opacity: .72;
}

.tarea-check-principal {
    width: 21px;
    height: 21px;
    cursor: pointer;
}

.tarjeta-tarea .badge {
    font-weight: 500;
}

.lista-subtareas {
    padding-left: 5px;
}

.fila-subtarea .form-control {
    flex: 1;
}


/* modo oscuro */

[data-bs-theme="dark"] .tarjeta-tarea {
    background-color: #242b33;
}

[data-bs-theme="dark"] .tarjeta-tarea:hover {
    box-shadow: 0 7px 22px rgba(0, 0, 0, .22) !important;
}

</style>


<?= $this->Html->script('tareas/tarea_funciones') ?>