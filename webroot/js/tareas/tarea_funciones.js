const URL_BASE = '/tareas';

document.addEventListener('DOMContentLoaded', () => {
    listarTareas();

    document.getElementById('btnNuevaTarea').addEventListener('click', () => abrirModal());

    document.getElementById('formTarea').addEventListener('submit', (e) => {
        e.preventDefault();
        guardarTarea();
    });
});

function listarTareas() {
    fetch(`${URL_BASE}/index`)
        .then(res => res.json())
        .then(data => {
            if (data.exito) renderizarTareas(data.datos);
            else console.error(data.mensaje);
        })
        .catch(err => console.error('Error al listar tareas:', err));
}

function renderizarTareas(tareas) {
    const contenedor = document.getElementById('listaTareas');
    contenedor.innerHTML = '';

    if (tareas.length === 0) {
        contenedor.innerHTML = '<p class="sin-tareas">No tienes tareas registradas.</p>';
        return;
    }

    tareas.forEach(tarea => {
        const asignadaPorEspecialista = tarea.id_especialista !== null;
        const completada = tarea.fecha_completada !== null;

        const item = document.createElement('div');
        item.className = 'tarjeta-tarea' + (completada ? ' completada' : '');
        item.style.borderLeftColor = tarea.categoria ? tarea.categoria.color : '#CCCCCC';

        const subtareasHtml = (tarea.subtareas && tarea.subtareas.length > 0)
            ? `<ul class="lista-subtareas">
                ${tarea.subtareas.map(sub => `
                    <li>
                        <input type="checkbox" ${sub.completada ? 'checked' : ''}
                               onchange="marcarSubtareaCompletada(${sub.id_subtarea}, this.checked)">
                        <span class="${sub.completada ? 'subtarea-completada' : ''}">${sub.titulo}</span>
                    </li>
                `).join('')}
               </ul>`
            : '';

        item.innerHTML = `
            <div class="tarea-check">
                <input type="checkbox" ${completada ? 'checked' : ''}
                       onchange="marcarCompletada(${tarea.id_tarea}, this.checked)">
            </div>
            <div class="tarea-info">
                <h4>${tarea.titulo}</h4>
                <p>${tarea.fecha_limite || ''} ${tarea.hora_limite ? '· ' + tarea.hora_limite : ''}</p>
                <span class="categoria-badge">${tarea.categoria ? tarea.categoria.nombre : 'Sin categoría'}</span>
                ${asignadaPorEspecialista ? '<span class="badge-especialista">Asignada por especialista</span>' : ''}
                ${subtareasHtml}
            </div>
            <div class="tarea-acciones">
                ${!asignadaPorEspecialista ? `
                    <button onclick="editarTarea(${tarea.id_tarea})">Editar</button>
                    <button onclick="eliminarTarea(${tarea.id_tarea})">Borrar</button>
                ` : ''}
            </div>
        `;

        contenedor.appendChild(item);
    });
}

function abrirModal(tarea = null) {
    document.getElementById('formTarea').reset();
    document.getElementById('idTarea').value = '';
    document.getElementById('listaSubtareasForm').innerHTML = '';

    if (tarea) {
        document.getElementById('modalTitulo').innerText = 'Editar tarea';
        document.getElementById('idTarea').value = tarea.id_tarea;
        document.getElementById('titulo').value = tarea.titulo;
        document.getElementById('fechaLimite').value = tarea.fecha_limite || '';
        document.getElementById('horaLimite').value = tarea.hora_limite || '';
        document.getElementById('notas').value = tarea.notas || '';
        document.getElementById('horaRecordatorio').value = tarea.hora_recordatorio || '';
        document.getElementById('idCategoria').value = tarea.id_categoria || '';

        if (tarea.subtareas && tarea.subtareas.length > 0) {
            tarea.subtareas.forEach(sub => agregarCampoSubtarea(sub.titulo));
        }
    } else {
        document.getElementById('modalTitulo').innerText = 'Nueva tarea';
    }

    document.getElementById('modalTarea').style.display = 'block';
}

function cerrarModal() {
    document.getElementById('modalTarea').style.display = 'none';
}

function editarTarea(id_tarea) {
    fetch(`${URL_BASE}/ver/${id_tarea}`)
        .then(res => res.json())
        .then(data => {
            if (data.exito) abrirModal(data.datos);
            else alert(data.mensaje);
        })
        .catch(err => console.error('Error al obtener tarea:', err));
}

function guardarTarea() {
    const id_tarea = document.getElementById('idTarea').value;
    const url = id_tarea ? `${URL_BASE}/editar/${id_tarea}` : `${URL_BASE}/agregar`;

    const payload = {
        titulo: document.getElementById('titulo').value,
        fecha_limite: document.getElementById('fechaLimite').value,
        hora_limite: document.getElementById('horaLimite').value,
        notas: document.getElementById('notas').value,
        hora_recordatorio: document.getElementById('horaRecordatorio').value,
        id_categoria: document.getElementById('idCategoria').value,
        subtareas: obtenerSubtareasDelForm()
    };

    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
        .then(res => res.json())
        .then(data => {
            if (data.exito) {
                cerrarModal();
                listarTareas();
            } else {
                alert(data.mensaje);
            }
        })
        .catch(err => console.error('Error al guardar tarea:', err));
}

function eliminarTarea(id_tarea) {
    if (!confirm('¿Seguro que deseas eliminar esta tarea?')) return;

    fetch(`${URL_BASE}/eliminar/${id_tarea}`, { method: 'POST' })
        .then(res => res.json())
        .then(data => {
            if (data.exito) listarTareas();
            else alert(data.mensaje);
        })
        .catch(err => console.error('Error al eliminar tarea:', err));
}

function marcarCompletada(id_tarea, completada) {
    fetch(`${URL_BASE}/marcar-completada/${id_tarea}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ completada })
    })
        .then(res => res.json())
        .then(() => listarTareas())
        .catch(err => console.error('Error al marcar tarea:', err));
}

function agregarCampoSubtarea(valor = '') {
    const contenedor = document.getElementById('listaSubtareasForm');
    const fila = document.createElement('div');
    fila.className = 'fila-subtarea';
    fila.innerHTML = `
        <input type="text" class="input-subtarea" value="${valor}" placeholder="Título de la subtarea" maxlength="50">
        <button type="button" onclick="this.parentElement.remove()">Quitar</button>
    `;
    contenedor.appendChild(fila);
}

function obtenerSubtareasDelForm() {
    const inputs = document.querySelectorAll('.input-subtarea');
    const subtareas = [];
    inputs.forEach(input => {
        if (input.value.trim() !== '') subtareas.push(input.value.trim());
    });
    return subtareas;
}

function marcarSubtareaCompletada(id_subtarea, completada) {
    fetch(`${URL_BASE}/marcar-subtarea-completada/${id_subtarea}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ completada })
    })
        .then(res => res.json())
        .then(() => listarTareas())
        .catch(err => console.error('Error al marcar subtarea:', err));
}