<!--
==================================================
PROGRESO
Muestra el avance de hábitos y tareas
de un socio (o del propio usuario en sesión).
==================================================
-->
<?php

/**
 * @var \App\View\AppView $this
 * @var bool $esPropio
 * @var int $idSocio
 * @var string|null $nombreSocio
 * @var array $resumenHabitos
 * @var int $porcentajeGeneralHabitos
 * @var int $totalTareas
 * @var int $tareasCompletadas
 * @var int $porcentajeTareas
 */

$this->assign('title', 'Progreso - Agendit');

?>

<div class="container-fluid">

    <div class="row mb-4">

        <div class="col">

            <h2>
                Progreso
                <?php if (!$esPropio): ?>
                    de <?= h($nombreSocio) ?>
                <?php endif; ?>
            </h2>

            <p class="text-muted">
                <?php if ($esPropio): ?>
                    Así vas con tus hábitos y tareas.
                <?php else: ?>
                    Avance de hábitos y tareas de tu socio.
                <?php endif; ?>
            </p>

        </div>

        <?php if (!$esPropio): ?>
            <div class="col-auto">
                <?= $this->Html->link('Volver a mis socios', [
                    'controller' => 'Vinculaciones',
                    'action' => 'misSocios'
                ], [
                    'class' => 'btn btn-secondary'
                ]) ?>
            </div>
        <?php endif; ?>

    </div>


    <!-- Resumen general -->
    <div class="row mb-4">

        <div class="col-md-6">

            <div class="card">

                <div class="card-body">

                    <h5 class="card-title">
                        Hábitos
                    </h5>

                    <div class="progress mb-2" style="height: 24px;">
                        <div class="progress-bar bg-success"
                             role="progressbar"
                             style="width: <?= $porcentajeGeneralHabitos ?>%;">
                            <?= $porcentajeGeneralHabitos ?>%
                        </div>
                    </div>

                    <p class="text-muted mb-0">
                        Cumplimiento general de todos los hábitos activos.
                    </p>

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="card">

                <div class="card-body">

                    <h5 class="card-title">
                        Tareas
                    </h5>

                    <div class="progress mb-2" style="height: 24px;">
                        <div class="progress-bar bg-primary"
                             role="progressbar"
                             style="width: <?= $porcentajeTareas ?>%;">
                            <?= $porcentajeTareas ?>%
                        </div>
                    </div>

                    <p class="text-muted mb-0">
                        <?= $tareasCompletadas ?> de <?= $totalTareas ?> tareas completadas.
                    </p>

                </div>

            </div>

        </div>

    </div>


    <!-- Detalle por hábito -->
    <div class="card">

        <div class="card-body">

            <h5 class="card-title mb-3">
                Detalle por hábito
            </h5>

            <?php if (empty($resumenHabitos)): ?>

                <div class="alert alert-info mb-0">
                    <?= $esPropio ? 'Aún no tienes hábitos registrados.' : 'Tu socio aún no tiene hábitos registrados.' ?>
                </div>

            <?php else: ?>

                <table class="table table-hover">

                    <thead>
                        <tr>
                            <th>Hábito</th>
                            <th>Completados</th>
                            <th>Esperados</th>
                            <th>Progreso</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($resumenHabitos as $h): ?>

                            <tr>

                                <td><?= h($h['titulo']) ?></td>

                                <td><?= $h['completadas'] ?></td>

                                <td><?= $h['esperadas'] ?></td>

                                <td>

                                    <div class="progress" style="height: 18px;">
                                        <div class="progress-bar bg-success"
                                             role="progressbar"
                                             style="width: <?= $h['porcentaje'] ?>%;">
                                            <?= $h['porcentaje'] ?>%
                                        </div>
                                    </div>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            <?php endif; ?>

        </div>

    </div>

</div>