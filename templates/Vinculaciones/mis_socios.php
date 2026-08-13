
<!--
==================================================
MIS SOCIOS
Esta vista muestra todos los usuarios vinculados
con el especialista que inició sesión.
==================================================
-->

<?php

/**
 * @var \Cake\Collection\CollectionInterface|\App\Model\Entity\Vinculacion[] $socios
 */

?>

<div class="container-fluid">

    <!-- ==================================================
         TÍTULO
         ================================================== -->

    <div class="row mb-4">

        <div class="col">

            <h2>
                Mis socios
            </h2>

            <p class="text-muted">
                Aquí aparecerán todos los usuarios
                vinculados contigo.
            </p>

        </div>

    </div>


    <?php if (empty($socios)): ?>

        <!-- ==================================================
             SIN SOCIOS
             ================================================== -->

        <div class="alert alert-info">

            Aún no tienes socios vinculados.

        </div>


    <?php else: ?>


        <!-- ==================================================
             TABLA DE SOCIOS
             ================================================== -->

        <div class="card">

            <div class="card-body">

                <table class="table table-hover">

                    <thead>

                        <tr>

                            <th>Nombre</th>

                            <th>Correo</th>

                            <th>Estado</th>

                            <th>Acciones</th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php foreach ($socios as $socio): ?>


                        <?php

                        /*
                         * Determinamos si la vinculación
                         * está activa.
                         */
                        $activo = $socio->estado === 'ACTIVA';

                        ?>


                        <tr>


                            <!-- ==================================================
                                 NOMBRE
                                 ================================================== -->

                            <td>

                                <?= h(
                                    $socio->usuario->nombre
                                    . ' '
                                    . $socio->usuario->apellido_paterno
                                ) ?>

                            </td>


                            <!-- ==================================================
                                 CORREO
                                 ================================================== -->

                            <td>

                                <?= h(
                                    $socio->usuario->email
                                ) ?>

                            </td>


                            <!-- ==================================================
                                 ESTADO
                                 ================================================== -->

                            <td>

                                <?php if ($activo): ?>

                                    <span class="badge bg-success">
                                        Activa
                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-secondary">
                                        Inactiva
                                    </span>

                                <?php endif; ?>

                            </td>


                            <!-- ==================================================
                                 ACCIONES
                                 ================================================== -->

                            <td>


                                <!-- ==================================================
                                     ASIGNAR TAREA
                                     ================================================== -->

                                <?php if ($activo): ?>

                                    <a
                                        href="<?= $this->Url->build([
                                            'controller' => 'Vinculaciones',
                                            'action' => 'agendaSocio',
                                            $socio->id_usuario
                                        ]) ?>"
                                        class="btn btn-primary btn-sm">

                                        Asignar tarea

                                    </a>

                                <?php else: ?>

                                    <button
                                        type="button"
                                        class="btn btn-primary btn-sm"
                                        onclick="mostrarSocioInactivo()">

                                        Asignar tarea

                                    </button>

                                <?php endif; ?>


                                <!-- ==================================================
                                     ASIGNAR HÁBITO
                                     ================================================== -->

                                <?php if ($activo): ?>

                                    <a
                                        href="/habitos/asignar/<?= h($socio->usuario->id_usuario) ?>"
                                        class="btn btn-success btn-sm">

                                        Asignar hábito

                                    </a>

                                <?php else: ?>

                                    <button
                                        type="button"
                                        class="btn btn-success btn-sm"
                                        onclick="mostrarSocioInactivo()">

                                        Asignar hábito

                                    </button>

                                <?php endif; ?>


                                <!-- ==================================================
                                     PROGRESO
                                     ================================================== -->

<a
    href="<?= $this->Url->build([
        'controller' => 'Vinculaciones',
        'action' => 'progresoSocio',
        $socio->id_usuario
    ]) ?>"
    class="btn btn-secondary btn-sm"
>
    <i class="ti ti-chart-bar me-1"></i>
    Progreso
</a>


                                <!-- ==================================================
                                     DAR DE BAJA / ACTIVAR
                                     ================================================== -->

                                <?php

                                /*
                                 * URL que utilizaremos para cambiar
                                 * el estado de la vinculación.
                                 */
                                $urlEstado = $this->Url->build([
                                    'controller' => 'Vinculaciones',
                                    'action' => 'cambiarEstado',
                                    $socio->id_vinculacion
                                ]);

                                ?>


                                <?php if ($activo): ?>

                                    <button
                                        type="button"
                                        class="btn btn-danger btn-sm"
                                        onclick="confirmarCambioEstado(
                                            '<?= h($urlEstado) ?>',
                                            'baja'
                                        )">

                                        Dar de baja

                                    </button>

                                <?php else: ?>

                                    <button
                                        type="button"
                                        class="btn btn-outline-success btn-sm"
                                        onclick="confirmarCambioEstado(
                                            '<?= h($urlEstado) ?>',
                                            'activar'
                                        )">

                                        Activar

                                    </button>

                                <?php endif; ?>


                            </td>


                        </tr>


                    <?php endforeach; ?>


                    </tbody>

                </table>

            </div>

        </div>


    <?php endif; ?>


</div>


<!-- ==================================================
     SWEETALERT2
     Ventanas de confirmación de Agendit
     ================================================== -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>

/*
 * ==================================================
 * CONFIRMAR CAMBIO DE ESTADO
 * ==================================================
 *
 * Muestra una ventana personalizada para:
 *
 * - Dar de baja
 * - Activar
 *
 * Ya no utiliza el confirm() del navegador.
 */
function confirmarCambioEstado(url, accion)
{

    /*
     * ==================================================
     * DAR DE BAJA
     * ==================================================
     */

    if (accion === 'baja') {

        Swal.fire({

            title: '¿Dar de baja a este socio?',

            text: 'El socio ya no podrá recibir tareas ni hábitos mientras esté inactivo.',

            icon: 'warning',

            showCancelButton: true,

            confirmButtonText: 'Sí, dar de baja',

            cancelButtonText: 'Cancelar',

            reverseButtons: true,

            buttonsStyling: true,

            confirmButtonColor: '#dc3545',

            cancelButtonColor: '#6c757d'

        }).then((resultado) => {

            if (resultado.isConfirmed) {

                /*
                 * Redirigimos al método
                 * cambiarEstado().
                 */
                window.location.href = url;

            }

        });

    }


    /*
     * ==================================================
     * ACTIVAR
     * ==================================================
     */

    else {

        Swal.fire({

            title: '¿Activar a este socio?',

            text: 'El socio podrá volver a recibir tareas y hábitos.',

            icon: 'question',

            showCancelButton: true,

            confirmButtonText: 'Sí, activar',

            cancelButtonText: 'Cancelar',

            reverseButtons: true,

            buttonsStyling: true,

            confirmButtonColor: '#198754',

            cancelButtonColor: '#6c757d'

        }).then((resultado) => {

            if (resultado.isConfirmed) {

                /*
                 * Redirigimos al método
                 * cambiarEstado().
                 */
                window.location.href = url;

            }

        });

    }

}


/*
 * ==================================================
 * SOCIO INACTIVO
 * ==================================================
 *
 * Mensaje que aparece al intentar asignar
 * una tarea o hábito a un socio inactivo.
 */
function mostrarSocioInactivo()
{

    Swal.fire({

        title: 'Socio inactivo',

        text: 'No puedes asignarle tareas ni hábitos porque actualmente está inactivo.',

        icon: 'info',

        confirmButtonText: 'Entendido',

        confirmButtonColor: '#198754'

    });

}

</script>

