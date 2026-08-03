<div class="container-fluid">

    <div class="row justify-content-center mt-5">

        <div class="col-md-6 col-lg-5">

            <div class="card">

                <div class="card-header">

                    <h3 class="mb-0">
                        Vincular con especialista
                    </h3>

                </div>

                <div class="card-body">

                    <p class="text-muted mb-4">
                        Ingresa el código de invitación que te proporcionó tu especialista
                        para comenzar el acompañamiento dentro de Agendit.
                    </p>


                    <?= $this->Form->create(null, [
                        'url' => [
                            'controller' => 'Vinculaciones',
                            'action' => 'validarCodigo'
                        ]
                    ]) ?>


                    <?= $this->Form->control('codigo', [

                        'label' => 'Código de invitación',

                        'placeholder' => 'Ejemplo: A82F91',

                        'class' => 'form-control',

                        'maxlength' => 6

                    ]) ?>


                    <div class="d-grid mt-4">

                        <?= $this->Form->button(

                            '<i class="ri-links-line me-1"></i> Vincularme',

                            [
                                'class' => 'btn btn-primary',

                                'escapeTitle' => false
                            ]

                        ) ?>

                    </div>

                    <?= $this->Form->end() ?>

                </div>

            </div>

        </div>

    </div>

</div>