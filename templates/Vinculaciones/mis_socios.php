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

    <!-- Título -->
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

        <div class="alert alert-info">

            Aún no tienes socios vinculados.

        </div>

    <?php else: ?>

    <!-- Tabla -->
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

                <?php
                /*
                 * Recorremos todos los socios
                 * enviados desde el controlador.
                 */
                foreach ($socios as $socio):
                ?>

                    <tr>

                        <!-- Nombre completo -->
                        <td>

                            <?= h(
                                $socio->usuario->nombre
                                . ' '
                                . $socio->usuario->apellido_paterno
                            ) ?>

                        </td>


                        <!-- Email -->
                        <td>

                            <?= h($socio->usuario->email) ?>

                        </td>


                        <!-- Estado -->
                        <td>

                            <span class="badge bg-success">

                                <?= h($socio->estado) ?>

                            </span>

                        </td>


                        <!-- Botones -->
                        <td>

                            <a href="<?= $this->Url->build([
                                'controller' => 'Vinculaciones',
                                'action' => 'agendaSocio',
                                    $socio->id_usuario
                            ]) ?>" class="btn btn-primary btn-sm">
                                Asignar tarea
                            </a>


                            <a href="/habitos/asignar/<?= h($socio->usuario->id_usuario) ?>"
                               class="btn btn-success btn-sm">

                                Asignar hábito

                            </a>


                            <a href="#"
                               class="btn btn-secondary btn-sm">

                                Progreso

                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

    <?php endif; ?>

</div>