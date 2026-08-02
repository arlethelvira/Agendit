<?php
/** @var \App\View\AppView $this */
?>
<h2>Mis Tareas</h2>

<button id="btnNuevaTarea">+ Nueva tarea</button>

<div id="listaTareas">
    <!-- tarea_funciones.js inyecta las tarjetas aquí -->
</div>

<div id="modalTarea" class="modal-overlay" style="display:none;">
    <div class="modal-contenido">
        <div class="modal-header">
            <h3 id="modalTitulo">Nueva tarea</h3>
            <span class="modal-cerrar" onclick="cerrarModal()">&times;</span>
        </div>

        <form id="formTarea">
            <input type="hidden" id="idTarea" name="id_tarea">

            <div class="campo-form">
                <label for="titulo">Título *</label>
                <input type="text" id="titulo" name="titulo" maxlength="30" required>
            </div>

            <div class="campo-form">
                <label for="fechaLimite">Fecha límite</label>
                <input type="date" id="fechaLimite" name="fecha_limite">
            </div>

            <div class="campo-form">
                <label for="horaLimite">Hora límite (opcional)</label>
                <input type="time" id="horaLimite" name="hora_limite">
            </div>

            <div class="campo-form">
                <label for="idCategoria">Categoría</label>
                <select id="idCategoria" name="id_categoria">
                    <option value="">Sin categoría</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat->id_categoria ?>">
                            <?= h($cat->nombre) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="campo-form">
                <label for="horaRecordatorio">Hora de recordatorio (opcional)</label>
                <input type="time" id="horaRecordatorio" name="hora_recordatorio">
            </div>

            <div class="campo-form">
                <label for="notas">Notas</label>
                <textarea id="notas" name="notas" maxlength="60" rows="3"></textarea>
            </div>

            <div class="campo-form">
                <label>Subtareas (opcional)</label>
                <div id="listaSubtareasForm"></div>
                <button type="button" onclick="agregarCampoSubtarea()">+ Agregar subtarea</button>
            </div>

            <div class="modal-acciones">
                <button type="button" class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
                <button type="submit" class="btn-guardar">Guardar</button>
            </div>
        </form>
    </div>
</div>

<?= $this->Html->script('tareas/tareaFunciones') ?>