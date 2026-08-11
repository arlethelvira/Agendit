<?php
/**
 * Vista de contenido únicamente — el layout ya pone
 * <html>, <head>, <body>, el wrapper y el menú.
 */
$this->assign('title', 'Calendario de Tareas - Agendit');
?>

<link href="/vendor/fullcalendar/main.min.css" rel="stylesheet" type="text/css" />

<div class="container-xxl">
    <?= $this->element('page-title', array('title' => 'Calendario', 'subTitle' => 'Mis Tareas')) ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-3">
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-primary" id="btn-new-tarea">
                                    <i class="bx bx-plus fs-18 me-2"></i>
                                    Nueva Tarea
                                </button>
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#categorias-modal">
                                    <i class="bx bx-palette fs-18 me-2"></i>
                                    Mis categorías
                                </button>
                            </div>
                            <br />
                            <p class="text-muted">
                                Da click en un día del calendario para crear una tarea en esa fecha,
                                o da click en una tarea existente para editarla.
                            </p>
                        </div>

                        <div class="col-xl-9">
                            <div class="mt-4 mt-lg-0">
                                <div id="calendar-tareas"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal crear/editar Tarea -->
            <div class="modal fade" id="tarea-modal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form class="needs-validation" id="form-tarea-calendario" novalidate>
                            <div class="modal-header p-3 border-bottom-0">
                                <h5 class="modal-title" id="tarea-modal-title">Tarea</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body px-3 pb-3 pt-0">
                                <input type="hidden" id="tarea-id" name="id_tarea">

                                <div class="mb-3">
                                    <label class="form-label">Título</label>
                                    <input class="form-control" type="text" name="titulo" id="tarea-titulo" maxlength="30" required />
                                    <div class="invalid-feedback">El título es obligatorio</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Fecha límite</label>
                                    <input class="form-control" type="date" name="fecha_limite" id="tarea-fecha" required />
                                    <div class="invalid-feedback">La fecha es obligatoria</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Hora límite (opcional)</label>
                                    <input class="form-control" type="time" name="hora_limite" id="tarea-hora" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Categoría</label>
                                    <select class="form-select" name="id_categoria" id="tarea-categoria">
                                        <option value="">Sin categoría</option>
                                        <?php foreach ($categorias as $cat): ?>
                                            <option value="<?= $cat->id_categoria ?>">
                                                <?= h($cat->nombre) ?>
                                            </option>
                                        <?php endforeach; ?>
                                        <option value="__nueva__">+ Nueva categoría</option>
                                    </select>

                                    <!-- Mini-formulario inline, oculto hasta que elijas "+ Nueva categoría" -->
                                    <div id="nueva-categoria-inline" class="d-none mt-2 p-2 border rounded">
                                        <div class="row g-2">
                                            <div class="col-7">
                                                <input type="text" class="form-control form-control-sm" id="nueva-cat-nombre" placeholder="Nombre" maxlength="50">
                                            </div>
                                            <div class="col-3">
                                                <input type="color" class="form-control form-control-sm form-control-color" id="nueva-cat-color" value="#6c757d">
                                            </div>
                                            <div class="col-2">
                                                <button type="button" class="btn btn-sm btn-success w-100" id="btn-guardar-cat-inline">✓</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Notas</label>
                                    <textarea class="form-control" name="notas" id="tarea-notas" maxlength="60"></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <button type="button" class="btn btn-danger" id="btn-delete-tarea">Eliminar</button>
                                    </div>
                                    <div class="col-6 text-end">
                                        <button type="button" class="btn btn-light me-1" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal administrar categorías -->
            <div class="modal fade" id="categorias-modal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Mis categorías</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <ul class="list-group mb-3" id="lista-categorias"></ul>

                            <div class="row g-2">
                                <div class="col-7">
                                    <input type="text" class="form-control" id="cat-modal-nombre" placeholder="Nombre" maxlength="50">
                                </div>
                                <div class="col-3">
                                    <input type="color" class="form-control form-control-color" id="cat-modal-color" value="#6c757d">
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-primary w-100" id="btn-agregar-categoria">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/vendor/fullcalendar/main.min.js"></script>
<script src="/js/tareas/tareas_calendar.js"></script>