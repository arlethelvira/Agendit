<?php
/**
 * @var string $idSocio
 * @var string $nombreSocio
 */
$this->assign('title', 'Agenda de ' . $nombreSocio . ' - Agendit');
?>

<link href="/vendor/fullcalendar/main.min.css" rel="stylesheet" type="text/css" />

<div class="container-xxl">
    <?= $this->element('page-title', array('title' => 'Agenda', 'subTitle' => $nombreSocio)) ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-3">
                            <div class="d-grid">
                                <button type="button" class="btn btn-primary" id="btn-new-tarea">
                                    <i class="bx bx-plus fs-18 me-2"></i>
                                    Asignar tarea
                                </button>
                            </div>
                            <br />
                            <p class="text-muted">
                                Los bloques en gris son horarios ocupados del socio (privados).
                                Los bloques de color son tareas que tú le has asignado.
                            </p>
                        </div>

                        <div class="col-xl-9">
                            <div class="mt-4 mt-lg-0">
                                <div id="calendar-agenda-socio"
                                     data-id-socio="<?= h($idSocio) ?>"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal asignar tarea -->
            <div class="modal fade" id="tarea-modal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form class="needs-validation" id="form-asignar-tarea" novalidate>
                            <div class="modal-header p-3 border-bottom-0">
                                <h5 class="modal-title">Asignar tarea a <?= h($nombreSocio) ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body px-3 pb-3 pt-0">
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
                                    <label class="form-label">Notas</label>
                                    <textarea class="form-control" name="notas" id="tarea-notas" maxlength="60"></textarea>
                                </div>

                                <div class="text-end">
                                    <button type="button" class="btn btn-danger me-auto d-none" id="btn-eliminar-tarea">
                                        Eliminar
                                    </button>
                                    <button type="button" class="btn btn-light me-1" data-bs-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary" id="btn-guardar-tarea">Asignar</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/vendor/fullcalendar/main.min.js"></script>
<script src="/js/vinculaciones/agenda_socio.js"></script>