
<!--
==================================================
VINCULAR CON ESPECIALISTA
Esta vista permite al usuario ingresar el código
de invitación proporcionado por su especialista.
==================================================
-->

<div class="container-fluid">


    <div class="row justify-content-center mt-5">


        <div class="col-md-6 col-lg-5">


            <div class="card">


                <!-- ==================================================
                     ENCABEZADO
                     ================================================== -->

                <div class="card-header">

                    <h3 class="mb-0">

                        Vincular con especialista

                    </h3>

                </div>


                <!-- ==================================================
                     CONTENIDO
                     ================================================== -->

                <div class="card-body">


                    <p class="text-muted mb-4">

                        Ingresa el código de invitación que te
                        proporcionó tu especialista para comenzar
                        el acompañamiento dentro de Agendit.

                    </p>


                    <!-- ==================================================
                         FORMULARIO
                         ================================================== -->

                    <?= $this->Form->create(null, [

                        'url' => [

                            'controller' => 'Vinculaciones',

                            'action' => 'validarCodigo'

                        ]

                    ]) ?>


                    <?= $this->Form->control('codigo', [

                        'label' =>
                            'Código de invitación',

                        'placeholder' =>
                            'Ejemplo: A82F91',

                        'class' =>
                            'form-control',

                        'maxlength' =>
                            6,

                        'autocomplete' =>
                            'off'

                    ]) ?>


                    <!-- ==================================================
                         BOTÓN
                         ================================================== -->

                    <div class="d-grid mt-4">


                        <?= $this->Form->button(

                            '<i class="ri-links-line me-1"></i> Vincularme',

                            [

                                'class' =>
                                    'btn btn-primary',

                                'escapeTitle' =>
                                    false

                            ]

                        ) ?>


                    </div>


                    <?= $this->Form->end() ?>


                </div>


            </div>


        </div>


    </div>


</div>


<!-- ==================================================
     SWEETALERT2
     ================================================== -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<?php if (!empty($alert)): ?>


<script>

Swal.fire({

    icon: '<?= h($alert['icon']) ?>',

    title: '<?= h($alert['title']) ?>',

    text: '<?= h($alert['text']) ?>',

    confirmButtonText: 'Entendido',

    confirmButtonColor: '#198754'

});

</script>


<?php endif; ?>

