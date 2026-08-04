<!doctype html>
<html lang="en">

<head>
    <?= $this->element('title-meta', array('title' => 'Schedule')) ?>

    <!-- Fullcalendar css -->
    <link href="/vendor/fullcalendar/main.min.css" rel="stylesheet" type="text/css" />

    <?= $this->element('head-css') ?>
</head>

<body>
    <!-- START Wrapper -->
    <div class="wrapper">
        <?= $this->element('menu') ?>

        <!-- ==================================================== -->
        <!-- Start right Content here -->
        <!-- ==================================================== -->
        <div class="page-content">
            <!-- Start Container -->
            <div class="container-xxl">
                <?= $this->element('page-title', array('title' => 'Schedule', 'subTitle' => 'Calendario de Hábitos')) ?>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-3">
                                        <div class="d-grid">
                                            <button type="button" class="btn btn-primary" id="btn-new-event">
                                                <i class="bx bx-plus fs-18 me-2"></i>
                                                Nuevo Hábito
                                            </button>
                                        </div>
                                        <br />
                                        <p class="text-muted">
                                            Da click en un día del calendario para crear un hábito en esa fecha,
                                            o da click en un hábito existente para editarlo.
                                        </p>
                                        <div class="mb-3">
                                            <label class="form-label">id_usuario a mostrar</label>
                                            <input type="number" class="form-control" id="filtro-id-usuario" value="4">
                                            <div class="form-text">Temporal, mientras se conecta con el login real.</div>
                                        </div>
                                    </div>
                                    <!-- end col-->

                                    <div class="col-xl-9">
                                        <div class="mt-4 mt-lg-0">
                                            <div id="calendar"></div>
                                        </div>
                                    </div>
                                    <!-- end col -->
                                </div>
                                <!-- end row -->
                            </div>
                            <!-- end card body-->
                        </div>
                        <!-- end card -->

                        <!-- Add/Edit Habito MODAL -->
                        <div class="modal fade" id="event-modal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form class="needs-validation" name="event-form" id="forms-event" novalidate>
                                        <div class="modal-header p-3 border-bottom-0">
                                            <h5 class="modal-title" id="modal-title">
                                                Hábito
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body px-3 pb-3 pt-0">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        <label class="control-label form-label">Título</label>
                                                        <input class="form-control" placeholder="Ej. Tomar agua" type="text" name="titulo" id="event-title" maxlength="30" required />
                                                        <div class="invalid-feedback">
                                                            El título es obligatorio
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        <label class="control-label form-label">Notas</label>
                                                        <textarea class="form-control" placeholder="Notas (opcional)" name="notas" id="event-notas" maxlength="60"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        <label class="control-label form-label">Frecuencia</label>
                                                        <select class="form-select" name="frecuencia" id="event-frecuencia" required>
                                                            <option value="" disabled selected>Selecciona una frecuencia</option>
                                                            <option value="diaria">Diaria</option>
                                                            <option value="cada 2 dias">Cada 2 días</option>
                                                            <option value="cada 3 dias">Cada 3 días</option>
                                                            <option value="semanal">Semanal</option>
                                                            <option value="mensual">Mensual</option>
                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Selecciona una frecuencia
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        <label class="control-label form-label">Color / Categoría</label>
                                                        <select class="form-select" name="category" id="event-category" required>
                                                            <option value="bg-primary">Azul</option>
                                                            <option value="bg-secondary">Gris</option>
                                                            <option value="bg-success">Verde</option>
                                                            <option value="bg-info">Cyan</option>
                                                            <option value="bg-warning">Amarillo</option>
                                                            <option value="bg-danger">Rojo</option>
                                                            <option value="bg-dark">Negro</option>
                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Selecciona un color
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <button type="button" class="btn btn-danger" id="btn-delete-event">
                                                        Eliminar
                                                    </button>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <button type="button" class="btn btn-light me-1" data-bs-dismiss="modal">
                                                        Cerrar
                                                    </button>
                                                    <button type="submit" class="btn btn-primary" id="btn-save-event">
                                                        Guardar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- end modal-content-->
                            </div>
                            <!-- end modal dialog-->
                        </div>
                        <!-- end modal-->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- End Container -->

            <?= $this->element('footer') ?>
        </div>
        <!-- ==================================================== -->
        <!-- End Page Content -->
        <!-- ==================================================== -->
    </div>
    <!-- END Wrapper -->

    <?= $this->element('vendor-scripts') ?>

    <!-- Full Calendar -->
    <script src="/vendor/fullcalendar/main.min.js"></script>

    <!-- Page Js -->
    <script src="/js/pages/app-calendar.js"></script>
</body>

</html>