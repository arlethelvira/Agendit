<?php
$this->assign('title', 'Dashboard');

$rol = $rol ?? ($usuario['rol'] ?? null);

$nombreUsuario = $usuario['nombre'] ?? 'Usuario';
?>


<?php if ($rol === 'usuario'): ?>

<div class="container-xxl">

    <?= $this->element('page-title', [
        'title' => 'Dashboard',
        'subTitle' => 'Agendit'
    ]) ?>


    <!-- =====================================================
         BIENVENIDA
    ====================================================== -->

    <div class="row mb-4">

        <div class="col-12">

            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">

                    <h2 class="fw-bold mb-2">
                        ¡Hola, <?= h($nombreUsuario) ?>! 👋
                    </h2>

                    <p class="text-muted mb-0">
                        Aquí tienes un resumen de tus actividades.
                    </p>

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         TARJETAS DE RESUMEN
    ====================================================== -->

    <div class="row g-3 mb-4">


        <!-- TAREAS PENDIENTES -->

        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div
                            class="
                                rounded-circle
                                bg-success-subtle
                                d-flex
                                align-items-center
                                justify-content-center
                                me-3
                            "
                            style="
                                width: 50px;
                                height: 50px;
                            "
                        >

                            <i
                                class="
                                    ti
                                    ti-checklist
                                    fs-24
                                    text-success
                                "
                            ></i>

                        </div>


                        <div>

                            <p class="text-muted mb-1">
                                Tareas pendientes
                            </p>

                            <h3 class="mb-0 fw-bold">
                                <?= $totalPendientes ?>
                            </h3>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- HÁBITOS -->

        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div
                            class="
                                rounded-circle
                                bg-success-subtle
                                d-flex
                                align-items-center
                                justify-content-center
                                me-3
                            "
                            style="
                                width: 50px;
                                height: 50px;
                            "
                        >

                            <i
                                class="
                                    ti
                                    ti-repeat
                                    fs-24
                                    text-success
                                "
                            ></i>

                        </div>


                        <div>

                            <p class="text-muted mb-1">
                                Mis hábitos
                            </p>

                            <h3 class="mb-0 fw-bold">
                                <?= $totalHabitos ?>
                            </h3>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- TAREAS DE HOY -->

        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div
                            class="
                                rounded-circle
                                bg-success-subtle
                                d-flex
                                align-items-center
                                justify-content-center
                                me-3
                            "
                            style="
                                width: 50px;
                                height: 50px;
                            "
                        >

                            <i
                                class="
                                    ti
                                    ti-calendar-event
                                    fs-24
                                    text-success
                                "
                            ></i>

                        </div>


                        <div>

                            <p class="text-muted mb-1">
                                Tareas para hoy
                            </p>

                            <h3 class="mb-0 fw-bold">
                                <?= $totalTareasHoy ?>
                            </h3>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         TAREAS DE HOY + HÁBITOS
    ====================================================== -->

    <div class="row g-3 mb-4">


        <!-- TAREAS DE HOY -->

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

                    <div
                        class="
                            d-flex
                            justify-content-between
                            align-items-center
                            gap-3
                        "
                    >

                        <div>

                            <h4 class="mb-1">
                                📝 Tareas de hoy
                            </h4>

                            <p class="text-muted mb-0">
                                Lo que tienes programado para hoy.
                            </p>

                        </div>


                        <a
                            href="<?= $this->Url->build('/tareas') ?>"
                            class="btn btn-success btn-sm"
                        >
                            Ver tareas
                        </a>

                    </div>

                </div>


                <div class="card-body px-4">


                    <?php if ($tareasHoy->count() === 0): ?>

                        <div class="text-center py-4">

                            <div class="fs-1 mb-2">
                                🎉
                            </div>

                            <h5>
                                No tienes tareas para hoy
                            </h5>

                            <p class="text-muted mb-0">
                                Puedes aprovechar para avanzar en otras actividades.
                            </p>

                        </div>


                    <?php else: ?>


                        <?php foreach ($tareasHoy as $tarea): ?>

                            <div
                                class="
                                    d-flex
                                    align-items-center
                                    border-bottom
                                    py-3
                                "
                            >

                                <div class="me-3">

                                    <?php if (!empty($tarea->fecha_completada)): ?>

                                        <i
                                            class="
                                                ti
                                                ti-circle-check
                                                text-success
                                                fs-24
                                            "
                                        ></i>

                                    <?php else: ?>

                                        <i
                                            class="
                                                ti
                                                ti-circle
                                                text-muted
                                                fs-24
                                            "
                                        ></i>

                                    <?php endif; ?>

                                </div>


                                <div class="flex-grow-1">

                                    <h6 class="mb-1">
                                        <?= h($tarea->titulo) ?>
                                    </h6>


                                    <?php if (!empty($tarea->hora_limite)): ?>

                                        <small class="text-muted">

                                            <i class="ti ti-clock"></i>

                                            <?= h(
                                                $tarea
                                                    ->hora_limite
                                                    ->format('H:i')
                                            ) ?>

                                        </small>

                                    <?php endif; ?>

                                </div>


                                <?php if (!empty($tarea->fecha_completada)): ?>

                                    <span
                                        class="
                                            badge
                                            bg-success-subtle
                                            text-success
                                        "
                                    >
                                        Completada
                                    </span>

                                <?php else: ?>

                                    <span
                                        class="
                                            badge
                                            bg-warning-subtle
                                            text-warning
                                        "
                                    >
                                        Pendiente
                                    </span>

                                <?php endif; ?>

                            </div>

                        <?php endforeach; ?>


                    <?php endif; ?>


                </div>

            </div>

        </div>


        <!-- HÁBITOS -->

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
                            align-items-center
                            gap-2
                        "
                    >

                        <div>

                            <h4 class="mb-1">
                                🌱 Mis hábitos
                            </h4>

                            <p class="text-muted mb-0">
                                Hábitos de tu rutina actual.
                            </p>

                        </div>


                        <a
                            href="<?= $this->Url->build('/habitos') ?>"
                            class="btn btn-outline-success btn-sm"
                        >
                            Ver hábitos
                        </a>

                    </div>

                </div>


                <div class="card-body px-4">


                    <?php if ($habitos->count() === 0): ?>

                        <div class="text-center py-4">

                            <div class="fs-1 mb-2">
                                🌱
                            </div>

                            <h5>
                                Aún no tienes hábitos
                            </h5>

                            <p class="text-muted mb-0">
                                Crea uno para comenzar tu rutina.
                            </p>

                        </div>


                    <?php else: ?>


                        <?php foreach ($habitos as $habito): ?>

                            <div
                                class="
                                    d-flex
                                    align-items-center
                                    border-bottom
                                    py-3
                                "
                            >

                                <div
                                    class="rounded-circle me-3 bg-success"
                                    style="
                                        width: 12px;
                                        height: 12px;
                                    "
                                ></div>


                                <div class="flex-grow-1">

                                    <h6 class="mb-1">
                                        <?= h($habito->titulo) ?>
                                    </h6>

                                    <small class="text-muted">
                                        <?= h($habito->frecuencia) ?>
                                    </small>

                                </div>


                                <?php if ($habito->creado_por === 'ESPECIALISTA'): ?>

                                    <span
                                        class="
                                            badge
                                            bg-info-subtle
                                            text-info
                                        "
                                    >
                                        Especialista
                                    </span>

                                <?php endif; ?>

                            </div>

                        <?php endforeach; ?>


                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         PROGRESO
    ====================================================== -->

    <div class="row g-3 mb-4">

        <div class="col-12">

            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">

                    <div
                        class="
                            d-flex
                            justify-content-between
                            align-items-center
                            mb-2
                        "
                    >

                        <div>

                            <h4 class="mb-1">
                                📊 Progreso de tus tareas
                            </h4>

                            <p class="text-muted mb-0">

                                <?= $totalCompletadas ?>

                                de

                                <?= $totalTareas ?>

                                tareas completadas.

                            </p>

                        </div>


                        <h4 class="text-success mb-0">
                            <?= $progreso ?>%
                        </h4>

                    </div>


                    <div
                        class="progress"
                        style="height: 10px;"
                    >

                        <div
                            class="
                                progress-bar
                                bg-success
                            "
                            role="progressbar"

                            style="
                                width:
                                <?= $progreso ?>%;
                            "

                            aria-valuenow="<?= $progreso ?>"

                            aria-valuemin="0"

                            aria-valuemax="100"
                        ></div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         PRÓXIMAS TAREAS
    ====================================================== -->

    <div class="row">

        <div class="col-12">

            <div class="card border-0 shadow-sm">

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
                        📅 Próximas tareas
                    </h4>

                    <p class="text-muted mb-0">
                        Tus siguientes actividades pendientes.
                    </p>

                </div>


                <div class="card-body px-4">


                    <?php if ($proximasTareas->count() === 0): ?>

                        <div class="text-center py-4">

                            <div class="fs-1 mb-2">
                                ✨
                            </div>

                            <h5>
                                No tienes próximas tareas
                            </h5>

                            <p class="text-muted mb-0">
                                Todo está bajo control.
                            </p>

                        </div>


                    <?php else: ?>


                        <?php foreach ($proximasTareas as $tarea): ?>

                            <div
                                class="
                                    d-flex
                                    align-items-center
                                    border-bottom
                                    py-3
                                    gap-3
                                "
                            >

                                <div class="text-center">

                                    <div class="fw-bold">

                                        <?= h(
                                            $tarea
                                                ->fecha_limite
                                                ->format('d')
                                        ) ?>

                                    </div>

                                    <small class="text-muted">

                                        <?= h(
                                            $tarea
                                                ->fecha_limite
                                                ->format('M')
                                        ) ?>

                                    </small>

                                </div>


                                <div class="flex-grow-1">

                                    <h6 class="mb-1">
                                        <?= h($tarea->titulo) ?>
                                    </h6>


                                    <?php if (!empty($tarea->categoria)): ?>

                                        <small class="text-muted">

                                            <?= h(
                                                $tarea
                                                    ->categoria
                                                    ->nombre
                                            ) ?>

                                        </small>

                                    <?php endif; ?>

                                </div>


                                <?php if (!empty($tarea->hora_limite)): ?>

                                    <span class="text-muted">

                                        <i class="ti ti-clock"></i>

                                        <?= h(
                                            $tarea
                                                ->hora_limite
                                                ->format('H:i')
                                        ) ?>

                                    </span>

                                <?php endif; ?>

                            </div>

                        <?php endforeach; ?>


                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

</div>

<?php endif; ?>



<?php if ($rol === 'especialista'): ?>

<div class="container-xxl">

    <?= $this->element('page-title', [
        'title' => 'Dashboard',
        'subTitle' => 'Especialista'
    ]) ?>


    <!-- =====================================================
         BIENVENIDA ESPECIALISTA
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

                            <h2 class="fw-bold mb-2">

                                ¡Hola, <?= h($nombreUsuario) ?>! 👋

                            </h2>

                            <p class="text-muted mb-0">
                                Aquí tienes un resumen de tus socios en Agendit.
                            </p>

                        </div>


                        <a
                            href="<?= $this->Url->build([
                                'controller' => 'Vinculaciones',
                                'action' => 'generarCodigo'
                            ]) ?>"
                            class="btn btn-success px-4"
                        >

                            <i class="ti ti-plus me-1"></i>

                            Generar código

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         ESTADÍSTICAS
    ====================================================== -->

    <div class="row g-3 mb-4">


        <!-- TOTAL -->

        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center">

                        <div
                            class="
                                rounded-circle
                                bg-success-subtle
                                text-success
                                d-flex
                                align-items-center
                                justify-content-center
                                me-3
                            "
                            style="
                                width: 52px;
                                height: 52px;
                            "
                        >

                            <i
                                class="
                                    ti
                                    ti-users
                                    fs-24
                                "
                            ></i>

                        </div>


                        <div>

                            <p class="text-muted mb-1">
                                Total de socios
                            </p>

                            <h3 class="fw-bold mb-0">
                                <?= $totalSocios ?>
                            </h3>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- ACTIVOS -->

        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center">

                        <div
                            class="
                                rounded-circle
                                bg-success-subtle
                                text-success
                                d-flex
                                align-items-center
                                justify-content-center
                                me-3
                            "
                            style="
                                width: 52px;
                                height: 52px;
                            "
                        >

                            <i
                                class="
                                    ti
                                    ti-user-check
                                    fs-24
                                "
                            ></i>

                        </div>


                        <div>

                            <p class="text-muted mb-1">
                                Socios activos
                            </p>

                            <h3 class="fw-bold mb-0">
                                <?= $totalSociosActivos ?>
                            </h3>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- INACTIVOS -->

        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center">

                        <div
                            class="
                                rounded-circle
                                bg-secondary-subtle
                                text-secondary
                                d-flex
                                align-items-center
                                justify-content-center
                                me-3
                            "
                            style="
                                width: 52px;
                                height: 52px;
                            "
                        >

                            <i
                                class="
                                    ti
                                    ti-user-off
                                    fs-24
                                "
                            ></i>

                        </div>


                        <div>

                            <p class="text-muted mb-1">
                                Socios inactivos
                            </p>

                            <h3 class="fw-bold mb-0">
                                <?= $totalSociosInactivos ?>
                            </h3>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         SOCIOS + ACCESOS
    ====================================================== -->

    <div class="row g-3">


        <!-- SOCIOS RECIENTES -->

        <div class="col-lg-8">

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
                            align-items-center
                            gap-3
                        "
                    >

                        <div>

                            <h4 class="mb-1">
                                👥 Mis socios
                            </h4>

                            <p class="text-muted mb-0">
                                Tus vinculaciones más recientes.
                            </p>

                        </div>


                        <a
                            href="<?= $this->Url->build([
                                'controller' => 'Vinculaciones',
                                'action' => 'misSocios'
                            ]) ?>"
                            class="
                                btn
                                btn-outline-success
                                btn-sm
                            "
                        >
                            Ver todos
                        </a>

                    </div>

                </div>


                <div class="card-body px-4">


                    <?php if ($sociosRecientes->count() === 0): ?>

                        <div class="text-center py-5">

                            <div class="fs-1 mb-2">
                                👥
                            </div>

                            <h5 class="mb-1">
                                Aún no tienes socios
                            </h5>

                            <p class="text-muted mb-3">
                                Genera un código para comenzar una vinculación.
                            </p>


                            <a
                                href="<?= $this->Url->build([
                                    'controller' => 'Vinculaciones',
                                    'action' => 'generarCodigo'
                                ]) ?>"
                                class="btn btn-success"
                            >
                                Generar código
                            </a>

                        </div>


                    <?php else: ?>


                        <?php foreach ($sociosRecientes as $vinculacion): ?>

                            <?php
                            $socio = $vinculacion->usuario ?? null;
                            ?>


                            <?php if ($socio): ?>

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

                                    <div
                                        class="
                                            d-flex
                                            align-items-center
                                            gap-3
                                        "
                                    >

                                        <!-- Inicial -->

                                        <div
                                            class="
                                                rounded-circle
                                                bg-success-subtle
                                                text-success
                                                d-flex
                                                align-items-center
                                                justify-content-center
                                                fw-bold
                                            "
                                            style="
                                                width: 46px;
                                                height: 46px;
                                            "
                                        >

                                            <?= h(
                                                strtoupper(
                                                    substr(
                                                        $socio->nombre,
                                                        0,
                                                        1
                                                    )
                                                )
                                            ) ?>

                                        </div>


                                        <!-- Datos -->

                                        <div>

                                            <h6 class="mb-1">

                                                <?= h(
                                                    $socio->nombre
                                                ) ?>

                                                <?= h(
                                                    $socio->apellido_paterno
                                                    ?? ''
                                                ) ?>

                                            </h6>


                                            <?php if (
                                                $vinculacion->estado === 'ACTIVA'
                                            ): ?>

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

                                                    Activo

                                                </span>

                                            <?php else: ?>

                                                <span
                                                    class="
                                                        badge
                                                        bg-secondary-subtle
                                                        text-secondary
                                                    "
                                                >

                                                    <i
                                                        class="
                                                            ti
                                                            ti-circle-x
                                                            me-1
                                                        "
                                                    ></i>

                                                    Inactivo

                                                </span>

                                            <?php endif; ?>

                                        </div>

                                    </div>


                                    <!-- VER AGENDA -->

                                    <?php if (
                                        $vinculacion->estado === 'ACTIVA'
                                    ): ?>

                                        <a
                                            href="<?= $this->Url->build([
                                                'controller' => 'Vinculaciones',
                                                'action' => 'agendaSocio',
                                                $vinculacion->id_usuario
                                            ]) ?>"
                                            class="
                                                btn
                                                btn-sm
                                                btn-outline-success
                                            "
                                        >

                                            <i
                                                class="
                                                    ti
                                                    ti-calendar
                                                    me-1
                                                "
                                            ></i>

                                            Ver agenda

                                        </a>

                                    <?php else: ?>

                                        <span class="text-muted small">
                                            Vinculación inactiva
                                        </span>

                                    <?php endif; ?>

                                </div>

                            <?php endif; ?>

                        <?php endforeach; ?>


                    <?php endif; ?>

                </div>

            </div>

        </div>


        <!-- =================================================
             ACCESOS RÁPIDOS
        ================================================== -->

        <div class="col-lg-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <h4 class="mb-1">
                        Accesos rápidos
                    </h4>

                    <p class="text-muted mb-4">
                        Gestiona tus socios fácilmente.
                    </p>


                    <div class="d-grid gap-3">


                        <!-- MIS SOCIOS -->

                        <a
                            href="<?= $this->Url->build([
                                'controller' => 'Vinculaciones',
                                'action' => 'misSocios'
                            ]) ?>"
                            class="
                                btn
                                btn-outline-success
                                text-start
                                p-3
                            "
                        >

                            <div class="d-flex align-items-center">

                                <i
                                    class="
                                        ti
                                        ti-users
                                        fs-22
                                        me-3
                                    "
                                ></i>

                                <div>

                                    <div class="fw-semibold">
                                        Mis socios
                                    </div>

                                    <small>
                                        Consulta y administra tus vinculaciones.
                                    </small>

                                </div>

                            </div>

                        </a>


                        <!-- GENERAR CÓDIGO -->

                        <a
                            href="<?= $this->Url->build([
                                'controller' => 'Vinculaciones',
                                'action' => 'generarCodigo'
                            ]) ?>"
                            class="
                                btn
                                btn-outline-success
                                text-start
                                p-3
                            "
                        >

                            <div class="d-flex align-items-center">

                                <i
                                    class="
                                        ti
                                        ti-copy
                                        fs-22
                                        me-3
                                    "
                                ></i>

                                <div>

                                    <div class="fw-semibold">
                                        Generar código
                                    </div>

                                    <small>
                                        Invita a un nuevo socio.
                                    </small>

                                </div>

                            </div>

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php endif; ?>



<?php if (!in_array($rol, ['usuario', 'especialista'], true)): ?>

<div class="container-xxl">

    <div class="alert alert-warning">
        No existe un dashboard disponible para este tipo de usuario.
    </div>

</div>

<?php endif; ?>