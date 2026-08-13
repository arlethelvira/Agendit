<?php
$this->assign('title', 'Progreso del socio');

$nombreSocio =
    trim(
        ($socio->nombre ?? '') . ' ' .
        ($socio->apellido_paterno ?? '') . ' ' .
        ($socio->apellido_materno ?? '')
    );
?>

<div class="container-xxl">

    <?= $this->element('page-title', [
        'title' => 'Progreso del socio',
        'subTitle' => 'Seguimiento'
    ]) ?>


    <!-- =====================================================
         ENCABEZADO
    ====================================================== -->

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

                            <span
                                class="
                                    badge
                                    bg-success-subtle
                                    text-success
                                    mb-2
                                "
                            >
                                Seguimiento del socio
                            </span>


                            <h2 class="fw-bold mb-2">

                                <?= h(
                                    $nombreSocio ?: 'Socio'
                                ) ?>

                            </h2>


                            <p class="text-muted mb-0">
                                Consulta el progreso de las actividades
                                que has asignado a este socio.
                            </p>

                        </div>


                        <div class="d-flex flex-wrap gap-2">

                            <a
                                href="<?= $this->Url->build([
                                    'controller' => 'Vinculaciones',
                                    'action' => 'agendaSocio',
                                    $vinculacion->id_usuario
                                ]) ?>"
                                class="btn btn-outline-success"
                            >
                                <i class="ti ti-calendar me-1"></i>
                                Ver agenda
                            </a>


                            <a
                                href="<?= $this->Url->build([
                                    'controller' => 'Vinculaciones',
                                    'action' => 'misSocios'
                                ]) ?>"
                                class="btn btn-light"
                            >
                                <i class="ti ti-arrow-left me-1"></i>
                                Volver
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         RESUMEN
    ====================================================== -->

    <div class="row g-3 mb-4">


        <!-- TOTAL ASIGNADAS -->

        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3">

                        <div
                            class="
                                rounded-circle
                                bg-success-subtle
                                text-success
                                d-flex
                                align-items-center
                                justify-content-center
                            "
                            style="
                                width:52px;
                                height:52px;
                            "
                        >

                            <i
                                class="
                                    ti
                                    ti-clipboard
                                    fs-24
                                "
                            ></i>

                        </div>


                        <div>

                            <p class="text-muted mb-1">
                                Tareas asignadas
                            </p>

                            <h3 class="fw-bold mb-0">
                                <?= $totalTareas ?>
                            </h3>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- COMPLETADAS -->

        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3">

                        <div
                            class="
                                rounded-circle
                                bg-success-subtle
                                text-success
                                d-flex
                                align-items-center
                                justify-content-center
                            "
                            style="
                                width:52px;
                                height:52px;
                            "
                        >

                            <i
                                class="
                                    ti
                                    ti-circle-check
                                    fs-24
                                "
                            ></i>

                        </div>


                        <div>

                            <p class="text-muted mb-1">
                                Completadas
                            </p>

                            <h3 class="fw-bold mb-0">
                                <?= $totalCompletadas ?>
                            </h3>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- PENDIENTES -->

        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3">

                        <div
                            class="
                                rounded-circle
                                bg-warning-subtle
                                text-warning
                                d-flex
                                align-items-center
                                justify-content-center
                            "
                            style="
                                width:52px;
                                height:52px;
                            "
                        >

                            <i
                                class="
                                    ti
                                    ti-clock
                                    fs-24
                                "
                            ></i>

                        </div>


                        <div>

                            <p class="text-muted mb-1">
                                Pendientes
                            </p>

                            <h3 class="fw-bold mb-0">
                                <?= $totalPendientes ?>
                            </h3>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- VENCIDAS -->

        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-3">

                        <div
                            class="
                                rounded-circle
                                bg-danger-subtle
                                text-danger
                                d-flex
                                align-items-center
                                justify-content-center
                            "
                            style="
                                width:52px;
                                height:52px;
                            "
                        >

                            <i
                                class="
                                    ti
                                    ti-alert-triangle
                                    fs-24
                                "
                            ></i>

                        </div>


                        <div>

                            <p class="text-muted mb-1">
                                Vencidas
                            </p>

                            <h3 class="fw-bold mb-0">
                                <?= $totalVencidas ?>
                            </h3>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         PROGRESO DE TAREAS
    ====================================================== -->

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
                            mb-3
                        "
                    >

                        <div>

                            <h4 class="mb-1">
                                Progreso de tareas asignadas
                            </h4>

                            <p class="text-muted mb-0">

                                El socio ha completado

                                <strong>
                                    <?= $totalCompletadas ?>
                                </strong>

                                de

                                <strong>
                                    <?= $totalTareas ?>
                                </strong>

                                tareas asignadas por ti.

                            </p>

                        </div>


                        <h2 class="text-success fw-bold mb-0">
                            <?= $porcentajeTareas ?>%
                        </h2>

                    </div>


                    <div
                        class="progress"
                        style="
                            height:12px;
                            border-radius:20px;
                        "
                    >

                        <div
                            class="
                                progress-bar
                                bg-success
                            "
                            role="progressbar"

                            style="
                                width:
                                <?= $porcentajeTareas ?>%;
                            "

                            aria-valuenow="<?= $porcentajeTareas ?>"
                            aria-valuemin="0"
                            aria-valuemax="100"
                        ></div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- =====================================================
         PROGRESO DE HÁBITOS
    ====================================================== -->

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
                            mb-3
                        "
                    >

                        <div>

                            <h4 class="mb-1">
                                Progreso de hábitos asignados
                            </h4>

                            <p class="text-muted mb-0">

                                Cumplimiento general de los hábitos
                                que has asignado a este socio.

                            </p>

                        </div>


                        <h2 class="text-success fw-bold mb-0">
                            <?= $porcentajeHabitos ?>%
                        </h2>

                    </div>


                    <div
                        class="progress"
                        style="
                            height:12px;
                            border-radius:20px;
                        "
                    >

                        <div
                            class="
                                progress-bar
                                bg-success
                            "
                            role="progressbar"

                            style="
                                width:
                                <?= $porcentajeHabitos ?>%;
                            "

                            aria-valuenow="<?= $porcentajeHabitos ?>"
                            aria-valuemin="0"
                            aria-valuemax="100"
                        ></div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         TAREAS + HÁBITOS
    ====================================================== -->

    <div class="row g-3">


        <!-- =================================================
             TAREAS ASIGNADAS
        ================================================== -->

        <div class="col-lg-7">

            <div class="card border-0 shadow-sm h-100">

                <div
                    class="
                        card-header
                        bg-transparent
                        border-0
                        pt-4
                        px-4
                    "
                >

                    <h4 class="mb-1">
                        <i
                            class="
                                ti
                                ti-clipboard-check
                                text-success
                                me-2
                            "
                        ></i>

                        Tareas asignadas
                    </h4>


                    <p class="text-muted mb-0">
                        Últimas tareas que has asignado a este socio.
                    </p>

                </div>


                <div class="card-body px-4">


                    <?php if ($tareasRecientes->count() === 0): ?>

                        <div class="text-center py-5">

                            <div class="fs-1 mb-2">
                                📝
                            </div>


                            <h5 class="mb-1">
                                No has asignado tareas
                            </h5>


                            <p class="text-muted mb-0">
                                Las tareas que asignes al socio
                                aparecerán aquí.
                            </p>

                        </div>


                    <?php else: ?>


                        <?php foreach ($tareasRecientes as $tarea): ?>

                            <?php

                            $completada =
                                !empty(
                                    $tarea->fecha_completada
                                );


                            $vencida = false;


                            if (
                                !$completada &&
                                !empty($tarea->fecha_limite)
                            ) {

                                $vencida =
                                    $tarea
                                        ->fecha_limite
                                        ->format('Y-m-d')
                                    <
                                    date('Y-m-d');
                            }

                            ?>


                            <div
                                class="
                                    d-flex
                                    flex-column
                                    flex-md-row
                                    justify-content-between
                                    align-items-md-center
                                    gap-3
                                    border-bottom
                                    py-3
                                "
                            >

                                <div class="flex-grow-1">


                                    <!-- TÍTULO -->

                                    <h6 class="mb-2">

                                        <?= h(
                                            $tarea->titulo
                                        ) ?>

                                    </h6>


                                    <div
                                        class="
                                            d-flex
                                            flex-wrap
                                            align-items-center
                                            gap-2
                                        "
                                    >


                                        <!-- ESTADO -->

                                        <?php if ($completada): ?>

                                            <span
                                                class="
                                                    badge
                                                    bg-success-subtle
                                                    text-success
                                                "
                                            >

                                                <i
                                                    class="
                                                        ti
                                                        ti-circle-check
                                                        me-1
                                                    "
                                                ></i>

                                                Completada

                                            </span>


                                        <?php elseif ($vencida): ?>

                                            <span
                                                class="
                                                    badge
                                                    bg-danger-subtle
                                                    text-danger
                                                "
                                            >

                                                <i
                                                    class="
                                                        ti
                                                        ti-alert-triangle
                                                        me-1
                                                    "
                                                ></i>

                                                Vencida

                                            </span>


                                        <?php else: ?>

                                            <span
                                                class="
                                                    badge
                                                    bg-warning-subtle
                                                    text-warning
                                                "
                                            >

                                                <i
                                                    class="
                                                        ti
                                                        ti-clock
                                                        me-1
                                                    "
                                                ></i>

                                                Pendiente

                                            </span>

                                        <?php endif; ?>


                                        <!-- CATEGORÍA -->

                                        <?php if (!empty($tarea->categoria)): ?>

                                            <span
                                                class="
                                                    badge
                                                    bg-secondary-subtle
                                                    text-secondary
                                                "
                                            >

                                                <?= h(
                                                    $tarea
                                                        ->categoria
                                                        ->nombre
                                                ) ?>

                                            </span>

                                        <?php endif; ?>

                                    </div>

                                </div>


                                <!-- FECHA -->

                                <?php if (!empty($tarea->fecha_limite)): ?>

                                    <div class="text-md-end">

                                        <small
                                            class="
                                                text-muted
                                                d-block
                                            "
                                        >
                                            Fecha límite
                                        </small>


                                        <span class="fw-medium">

                                            <?= h(
                                                $tarea
                                                    ->fecha_limite
                                                    ->format(
                                                        'd/m/Y'
                                                    )
                                            ) ?>

                                        </span>

                                    </div>

                                <?php endif; ?>

                            </div>

                        <?php endforeach; ?>


                    <?php endif; ?>

                </div>

            </div>

        </div>


        <!-- =================================================
             HÁBITOS ASIGNADOS
        ================================================== -->

        <div class="col-lg-5">

            <div class="card border-0 shadow-sm h-100">

                <div
                    class="
                        card-header
                        bg-transparent
                        border-0
                        pt-4
                        px-4
                    "
                >

                    <div
                        class="
                            d-flex
                            justify-content-between
                            align-items-start
                            gap-2
                        "
                    >

                        <div>

                            <h4 class="mb-1">

                                <i
                                    class="
                                        ti
                                        ti-leaf
                                        text-success
                                        me-2
                                    "
                                ></i>

                                Hábitos asignados

                            </h4>


                            <p class="text-muted mb-0">
                                Hábitos que tú has asignado al socio.
                            </p>

                        </div>


                        <span
                            class="
                                badge
                                bg-success-subtle
                                text-success
                            "
                        >
                            <?= $totalHabitos ?>
                        </span>

                    </div>

                </div>


                <div class="card-body px-4">


                    <?php if ($habitos->count() === 0): ?>

                        <div class="text-center py-5">

                            <div class="fs-1 mb-2">
                                🌱
                            </div>


                            <h5 class="mb-1">
                                No has asignado hábitos
                            </h5>


                            <p class="text-muted mb-0">
                                Cuando asignes uno aparecerá aquí.
                            </p>

                        </div>


                    <?php else: ?>

                        $resumenPorHabito = [];
                        foreach ($resumenHabitos as $r) {
                            $resumenPorHabito[$r['id_habito']] = $r;
                        }
                        ?>

                        <?php foreach ($habitos as $habito): ?>

                            <div
                                class="
                                    d-flex
                                    gap-3
                                    border-bottom
                                    py-3
                                "
                            >

                                <!-- COLOR -->

                                <div
                                    class="
                                        rounded-circle
                                        bg-success
                                        mt-1
                                    "
                                    style="
                                        width:11px;
                                        height:11px;
                                        flex-shrink:0;
                                    "
                                ></div>


                                <!-- DATOS -->

                                <div class="flex-grow-1">

                                    <h6 class="mb-1">

                                        <?= h(
                                            $habito->titulo
                                        ) ?>

                                    </h6>


                                    <p
                                        class="
                                            text-muted
                                            small
                                            mb-2
                                        "
                                    >

                                        <i
                                            class="
                                                ti
                                                ti-repeat
                                                me-1
                                            "
                                        ></i>

                                        <?= h(
                                            $habito->frecuencia
                                            ?: 'Sin frecuencia'
                                        ) ?>

                                    </p>


                                    <?php if (!empty($habito->notas)): ?>

                                        <p
                                            class="
                                                text-muted
                                                small
                                                mb-0
                                            "
                                        >

                                            <?= h(
                                                $habito->notas
                                            ) ?>

                                        </p>

                                    <?php endif; ?>


                                    <span
                                        class="
                                            badge
                                            bg-success-subtle
                                            text-success
                                            mt-2
                                        "
                                    >

                                        <i
                                            class="
                                                ti
                                                ti-stethoscope
                                                me-1
                                            "
                                        ></i>

                                        Asignado por ti

                                    </span>
                                    <span
                                        class="
                                            badge
                                            bg-success-subtle
                                            text-success
                                            mt-2
                                        "
                                    >

                                        <i
                                            class="
                                                ti
                                                ti-stethoscope
                                                me-1
                                            "
                                        ></i>

                                        Asignado por ti

                                    </span>


                                    <?php
                                    $r = $resumenPorHabito[$habito->id_habito] ?? null;
                                    ?>

                                    <?php if ($r): ?>

                                        <div class="mt-2">

                                            <div class="d-flex justify-content-between align-items-center mb-1">

                                                <small class="text-muted">
                                                    <?= $r['completadas'] ?> de <?= $r['esperadas'] ?> cumplidos
                                                </small>

                                                <small class="fw-bold text-success">
                                                    <?= $r['porcentaje'] ?>%
                                                </small>

                                            </div>

                                            <div class="progress" style="height: 6px;">

                                                <div
                                                    class="progress-bar bg-success"
                                                    role="progressbar"
                                                    style="width: <?= $r['porcentaje'] ?>%;"
                                                ></div>

                                            </div>

                                        </div>

                                    <?php endif; ?>
                                    
                                </div>

                            </div>

                        <?php endforeach; ?>


                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         AVISO DE PRIVACIDAD
    ====================================================== -->

    <div class="row mt-4">

        <div class="col-12">

            <div
                class="
                    card
                    border-0
                    bg-success-subtle
                "
            >

                <div class="card-body py-3 px-4">

                    <div
                        class="
                            d-flex
                            align-items-start
                            gap-2
                            text-success-emphasis
                        "
                    >

                        <i
                            class="
                                ti
                                ti-shield-lock
                                fs-20
                                mt-1
                            "
                        ></i>


                        <div>

                            <strong>
                                Privacidad del socio
                            </strong>


                            <p class="small mb-0 mt-1">

                                Esta sección únicamente muestra
                                las tareas y hábitos que tú has
                                asignado. Las actividades personales
                                creadas por el socio permanecen privadas.

                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>