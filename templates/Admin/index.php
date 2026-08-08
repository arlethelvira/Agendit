<div class="card">

    <div class="card-header">
        <h4 class="card-title mb-1">
            Solicitudes de especialistas
        </h4>

        <p class="text-muted mb-0">
            Aquí puedes revisar y gestionar las solicitudes de los especialistas registrados.
        </p>
    </div>

    <div class="card-body">

        <?php if ($especialistas->isEmpty()): ?>

            <div class="alert alert-info">
                No hay especialistas pendientes.
            </div>

        <?php else: ?>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Apellido paterno</th>
                            <th>Apellido materno</th>
                            <th>Especialidad</th>
                            <th>Cédula profesional</th>
                            <th>Email</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php $contador = 1; ?>

                        <?php foreach ($especialistas as $especialista): ?>

                            <tr>

                                <!-- Número -->
                                <td>
                                    <?= $contador++ ?>
                                </td>

                                <!-- Nombre -->
                                <td>
                                    <?= h($especialista->usuario->nombre ?? 'Sin nombre') ?>
                                </td>

                                <!-- Apellido paterno -->
                                <td>
                                    <?= h($especialista->usuario->apellido_paterno ?? 'Sin dato') ?>
                                </td>

                                <!-- Apellido materno -->
                                <td>
                                    <?= h($especialista->usuario->apellido_materno ?? 'Sin dato') ?>
                                </td>

                                <!-- Especialidad -->
                                <td>
                                    <?= h($especialista->tipo_especialista->nombre ?? 'Sin especialidad') ?>
                                </td>

                                <!-- Cédula profesional -->
                                <td>
                                    <?= h($especialista->cedula_profesional) ?>
                                </td>

                                <!-- Email -->
                                <td>
                                    <?= h($especialista->usuario->email ?? 'Sin email') ?>
                                </td>

                                <!-- Acciones -->
                                <td>

                                    <div class="d-flex gap-2">

                                        <!-- ========================= -->
                                        <!-- ACEPTAR ESPECIALISTA -->
                                        <!-- ========================= -->

                                        <?= $this->Form->postLink(
                                            'Aceptar',
                                            [
                                                'controller' => 'Admin',
                                                'action' => 'aceptar',
                                                $especialista->id_especialista
                                            ],
                                            [
                                                'confirm' => '¿Estás seguro de aceptar a este especialista?',
                                                'class' => 'btn btn-success btn-sm'
                                            ]
                                        ) ?>


                                        <!-- ========================= -->
                                        <!-- RECHAZAR ESPECIALISTA -->
                                        <!-- ========================= -->

                                        <?= $this->Form->postLink(
                                            'Rechazar',
                                            [
                                                'controller' => 'Admin',
                                                'action' => 'rechazar',
                                                $especialista->id_especialista
                                            ],
                                            [
                                                'confirm' => '¿Estás seguro de rechazar a este especialista?',
                                                'class' => 'btn btn-danger btn-sm'
                                            ]
                                        ) ?>

                                    </div>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>

    </div>

</div>