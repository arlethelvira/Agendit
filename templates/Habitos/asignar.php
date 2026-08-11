<!--
==================================================
ASIGNAR HÁBITO
Formulario para que el especialista asigne
un hábito nuevo a uno de sus socios vinculados.
==================================================
-->
<?php

/**
 * @var \App\View\AppView $this
 * @var int $idUsuario
 */

?>

<div class="container-fluid">

    <div class="row mb-4">

        <div class="col">

            <h2>
                Asignar hábito
            </h2>

            <p class="text-muted">
                Este hábito se creará directamente
                para tu socio.
            </p>

        </div>

    </div>


    <div class="card">

        <div class="card-body">

            <?= $this->Form->create(null, [
                'url' => [
                    'controller' => 'Habitos',
                    'action' => 'asignar',
                    $idUsuario
                ]
            ]) ?>


                <!-- Título -->
                <div class="mb-3">

                    <?= $this->Form->control('titulo', [
                        'label' => 'Título',
                        'class' => 'form-control',
                        'maxlength' => 30,
                        'required' => true
                    ]) ?>

                </div>


                <!-- Notas -->
                <div class="mb-3">

                    <?= $this->Form->control('notas', [
                        'label' => 'Notas',
                        'class' => 'form-control',
                        'maxlength' => 60,
                        'type' => 'textarea'
                    ]) ?>

                </div>


                <!-- Frecuencia -->
                <div class="mb-3">

                    <?= $this->Form->control('frecuencia', [
                        'label' => 'Frecuencia',
                        'type' => 'select',
                        'class' => 'form-control',
                        'options' => [
                            'diaria' => 'Diaria',
                            'cada 2 dias' => 'Cada 2 días',
                            'cada 3 dias' => 'Cada 3 días',
                            'semanal' => 'Semanal',
                            'mensual' => 'Mensual',
                        ],
                        'empty' => 'Selecciona una frecuencia',
                        'required' => true
                    ]) ?>

                </div>


                <!-- Fecha de creación (oculta, se manda automática) -->
                <?= $this->Form->hidden('fecha_creacion', [
                    'value' => date('Y-m-d H:i:s')
                ]) ?>


                <div class="d-flex gap-2">

                    <?= $this->Form->button('Asignar hábito', [
                        'class' => 'btn btn-success'
                    ]) ?>

                    <?= $this->Html->link('Cancelar', [
                        'controller' => 'Vinculaciones',
                        'action' => 'misSocios'
                    ], [
                        'class' => 'btn btn-secondary'
                    ]) ?>

                </div>


            <?= $this->Form->end() ?>

        </div>

    </div>

</div>