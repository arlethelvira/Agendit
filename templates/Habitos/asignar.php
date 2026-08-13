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

    <label
        class="control-label form-label"
        for="event-frecuencia"
    >
        Frecuencia
    </label>

    <select
        class="form-select"
        name="frecuencia_tipo"
        id="event-frecuencia"
        required
    >

        <option value="" disabled selected>
            Selecciona una frecuencia
        </option>

        <option value="diaria">
            Todos los días
        </option>

        <option value="cada 2 dias">
            Cada 2 días
        </option>

        <option value="cada 3 dias">
            Cada 3 días
        </option>

        <option value="semanal">
            Una vez por semana
        </option>

        <option value="mensual">
            Una vez al mes
        </option>

        <option value="dias_especificos">
            Días específicos
        </option>

    </select>

    <div class="invalid-feedback">
        Selecciona una frecuencia.
    </div>

</div>


<!-- ==========================================
     DÍAS ESPECÍFICOS
========================================== -->

<div
    id="contenedor-dias-especificos"
    class="mb-3"
    style="display: none;"
>

    <label class="form-label">
        ¿Qué días debe realizarse?
    </label>


    <div class="row g-2">


        <!-- LUNES -->
        <div class="col-6 col-md-4">

            <div class="form-check">

                <input
                    class="form-check-input dia-habito"
                    type="checkbox"
                    value="lunes"
                    id="dia-lunes"
                >

                <label
                    class="form-check-label"
                    for="dia-lunes"
                >
                    Lunes
                </label>

            </div>

        </div>


        <!-- MARTES -->
        <div class="col-6 col-md-4">

            <div class="form-check">

                <input
                    class="form-check-input dia-habito"
                    type="checkbox"
                    value="martes"
                    id="dia-martes"
                >

                <label
                    class="form-check-label"
                    for="dia-martes"
                >
                    Martes
                </label>

            </div>

        </div>


        <!-- MIÉRCOLES -->
        <div class="col-6 col-md-4">

            <div class="form-check">

                <input
                    class="form-check-input dia-habito"
                    type="checkbox"
                    value="miercoles"
                    id="dia-miercoles"
                >

                <label
                    class="form-check-label"
                    for="dia-miercoles"
                >
                    Miércoles
                </label>

            </div>

        </div>


        <!-- JUEVES -->
        <div class="col-6 col-md-4">

            <div class="form-check">

                <input
                    class="form-check-input dia-habito"
                    type="checkbox"
                    value="jueves"
                    id="dia-jueves"
                >

                <label
                    class="form-check-label"
                    for="dia-jueves"
                >
                    Jueves
                </label>

            </div>

        </div>


        <!-- VIERNES -->
        <div class="col-6 col-md-4">

            <div class="form-check">

                <input
                    class="form-check-input dia-habito"
                    type="checkbox"
                    value="viernes"
                    id="dia-viernes"
                >

                <label
                    class="form-check-label"
                    for="dia-viernes"
                >
                    Viernes
                </label>

            </div>

        </div>


        <!-- SÁBADO -->
        <div class="col-6 col-md-4">

            <div class="form-check">

                <input
                    class="form-check-input dia-habito"
                    type="checkbox"
                    value="sabado"
                    id="dia-sabado"
                >

                <label
                    class="form-check-label"
                    for="dia-sabado"
                >
                    Sábado
                </label>

            </div>

        </div>


        <!-- DOMINGO -->
        <div class="col-6 col-md-4">

            <div class="form-check">

                <input
                    class="form-check-input dia-habito"
                    type="checkbox"
                    value="domingo"
                    id="dia-domingo"
                >

                <label
                    class="form-check-label"
                    for="dia-domingo"
                >
                    Domingo
                </label>

            </div>

        </div>

    </div>


    <small
        id="error-dias-habito"
        class="text-danger"
        style="display: none;"
    >
        Selecciona al menos un día.
    </small>

</div>


<!-- Este será el valor que guardaremos -->
<input
    type="hidden"
    name="frecuencia"
    id="frecuencia-final"
>


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
<script>
document.addEventListener('DOMContentLoaded', function () {

    const selectFrecuencia =
        document.getElementById('event-frecuencia');

    const contenedorDias =
        document.getElementById('contenedor-dias-especificos');

    const frecuenciaFinal =
        document.getElementById('frecuencia-final');

    const checkDias =
        document.querySelectorAll('.dia-habito');

    const errorDias =
        document.getElementById('error-dias-habito');

    const formulario =
        selectFrecuencia.closest('form');


    /*
     * ==========================================
     * MOSTRAR / OCULTAR DÍAS
     * ==========================================
     */
    selectFrecuencia.addEventListener('change', function () {

        if (this.value === 'dias_especificos') {

            contenedorDias.style.display = 'block';

            frecuenciaFinal.value = '';

        } else {

            contenedorDias.style.display = 'none';

            errorDias.style.display = 'none';

            /*
             * Guardamos directamente la frecuencia.
             *
             * Ejemplo:
             * diaria
             * cada 2 dias
             * semanal
             */
            frecuenciaFinal.value = this.value;


            /*
             * Limpiamos los días seleccionados.
             */
            checkDias.forEach(function (check) {

                check.checked = false;

            });

        }

    });


    /*
     * ==========================================
     * ANTES DE ENVIAR
     * ==========================================
     */
    formulario.addEventListener('submit', function (e) {

        /*
         * Frecuencia normal.
         */
        if (
            selectFrecuencia.value !==
            'dias_especificos'
        ) {

            frecuenciaFinal.value =
                selectFrecuencia.value;

            return;

        }


        /*
         * ======================================
         * DÍAS ESPECÍFICOS
         * ======================================
         */

        const diasSeleccionados = [];


        checkDias.forEach(function (check) {

            if (check.checked) {

                diasSeleccionados.push(
                    check.value
                );

            }

        });


        /*
         * Tiene que seleccionar mínimo un día.
         */
        if (diasSeleccionados.length === 0) {

            e.preventDefault();

            errorDias.style.display =
                'block';

            return;

        }


        errorDias.style.display =
            'none';


        /*
         * Ejemplo que llegará al backend:
         *
         * lunes,miercoles,viernes
         */
        frecuenciaFinal.value =
            diasSeleccionados.join(',');

    });

});
</script>