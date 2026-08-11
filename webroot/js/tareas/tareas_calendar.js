class TareasCalendar {
    constructor() {
        this.modal = new bootstrap.Modal(document.getElementById("tarea-modal"), { backdrop: "static" });
        this.calendarEl = document.getElementById("calendar-tareas");
        this.form = document.getElementById("form-tarea-calendario");
        this.btnNew = document.getElementById("btn-new-tarea");
        this.btnDelete = document.getElementById("btn-delete-tarea");
        this.modalTitle = document.getElementById("tarea-modal-title");
        this.calendarObj = null;

        // Elementos del selector de categoría (modal de tarea)
        this.selectCategoria = document.getElementById("tarea-categoria");
        this.inlineForm = document.getElementById("nueva-categoria-inline");
        this.inputNombre = document.getElementById("nueva-cat-nombre");
        this.inputColor = document.getElementById("nueva-cat-color");
        this.btnGuardarCat = document.getElementById("btn-guardar-cat-inline");

        // Elementos del modal "Mis categorías"
        this.listaCategorias = document.getElementById("lista-categorias");
        this.catModalNombre = document.getElementById("cat-modal-nombre");
        this.catModalColor = document.getElementById("cat-modal-color");
        this.btnAgregarCategoria = document.getElementById("btn-agregar-categoria");
        this.categoriasModalEl = document.getElementById("categorias-modal");
    }

    cargarEventos(fetchInfo, successCallback, failureCallback) {
        fetch('/tareas/eventos')
            .then(res => res.json())
            .then(data => {
                if (data.exito) successCallback(data.datos);
                else failureCallback();
            })
            .catch(() => failureCallback());
    }

    abrirModalNuevo(fechaSeleccionada) {
        this.form.reset();
        this.form.classList.remove("was-validated");
        document.getElementById("tarea-id").value = '';
        document.getElementById("tarea-fecha").value = fechaSeleccionada || '';
        this.inlineForm.classList.add("d-none");
        this.btnDelete.style.display = "none";
        this.modalTitle.textContent = "Nueva tarea";
        this.modal.show();
    }

    abrirModalEditar(eventInfo) {
        this.form.reset();
        this.form.classList.remove("was-validated");
        this.inlineForm.classList.add("d-none");

        const props = eventInfo.extendedProps;

        console.log('DEBUG props:', props);
    console.log('DEBUG idCategoria:', props.idCategoria);

    
        console.log('DEBUG props:', props);
        document.getElementById("tarea-id").value = eventInfo.id;
        document.getElementById("tarea-titulo").value = eventInfo.title;
        document.getElementById("tarea-notas").value = props.notas || '';
         document.getElementById("tarea-categoria").value = props.idCategoria || '';
        document.getElementById("nueva-categoria-inline").classList.add("d-none");

        const start = eventInfo.startStr;
        if (start) {
            document.getElementById("tarea-fecha").value = start.substring(0, 10);
            if (start.includes('T')) {
                document.getElementById("tarea-hora").value = start.substring(11, 16);
            }
        }

        this.btnDelete.style.display = "block";
        this.modalTitle.textContent = "Editar tarea";
        this.modal.show();
    }

    guardarTarea() {
        const idTarea = document.getElementById("tarea-id").value;
        const url = idTarea ? `/tareas/editar/${idTarea}` : '/tareas/agregar';

        const payload = {
            titulo: document.getElementById("tarea-titulo").value,
            fecha_limite: document.getElementById("tarea-fecha").value,
            hora_limite: document.getElementById("tarea-hora").value,
            id_categoria: document.getElementById("tarea-categoria").value,
            notas: document.getElementById("tarea-notas").value,
            subtareas: []
        };

        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
            .then(res => res.json())
            .then(data => {
                if (data.exito) {
                    this.modal.hide();
                    this.calendarObj.refetchEvents();
                } else {
                    alert(data.mensaje);
                }
            });
    }

    eliminarTarea() {
        const idTarea = document.getElementById("tarea-id").value;
        if (!idTarea || !confirm('¿Eliminar esta tarea?')) return;

        fetch(`/tareas/eliminar/${idTarea}`, { method: 'POST' })
            .then(res => res.json())
            .then(data => {
                if (data.exito) {
                    this.modal.hide();
                    this.calendarObj.refetchEvents();
                } else {
                    alert(data.mensaje);
                }
            });
    }

    // ===== Gestión de categorías =====

    initCategorias() {
        const self = this;

        // Dropdown dentro del modal de tarea: mostrar mini-form al elegir "+ Nueva categoría"
        this.selectCategoria.addEventListener("change", function () {
            if (this.value === "__nueva__") {
                self.inlineForm.classList.remove("d-none");
                self.inputNombre.focus();
            } else {
                self.inlineForm.classList.add("d-none");
            }
        });

        // Guardar categoría desde el mini-form inline
        this.btnGuardarCat.addEventListener("click", function () {
            const nombre = self.inputNombre.value.trim();
            const color = self.inputColor.value;
            if (!nombre) {
                self.inputNombre.focus();
                return;
            }

            fetch('/categorias/agregar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ nombre, color })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.exito) {
                        const nuevaOpcion = document.createElement("option");
                        nuevaOpcion.value = data.datos.id_categoria;
                        nuevaOpcion.textContent = data.datos.nombre;
                        self.selectCategoria.insertBefore(nuevaOpcion, self.selectCategoria.lastElementChild);
                        self.selectCategoria.value = data.datos.id_categoria;
                        self.inlineForm.classList.add("d-none");
                        self.inputNombre.value = "";
                    } else {
                        alert(data.mensaje);
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Ocurrió un error al crear la categoría.');
                });
        });

        // Al abrir el modal "Mis categorías", cargamos la lista
        this.categoriasModalEl.addEventListener("show.bs.modal", () => self.cargarListaCategorias());

        // Agregar categoría desde el modal "Mis categorías"
        this.btnAgregarCategoria.addEventListener("click", function () {
            const nombre = self.catModalNombre.value.trim();
            const color = self.catModalColor.value;
            if (!nombre) return;

            fetch('/categorias/agregar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ nombre, color })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.exito) {
                        self.catModalNombre.value = "";
                        self.cargarListaCategorias();
                        self.recargarDropdownCategorias();
                    } else {
                        alert(data.mensaje);
                    }
                });
        });
    }

    cargarListaCategorias() {
        const self = this;
        fetch('/categorias/index')
            .then(res => res.json())
            .then(data => {
                self.listaCategorias.innerHTML = "";
                if (!data.exito || data.datos.length === 0) {
                    self.listaCategorias.innerHTML = '<li class="list-group-item text-muted">Aún no tienes categorías</li>';
                    return;
                }
                data.datos.forEach(cat => {
                    const li = document.createElement("li");
                    li.className = "list-group-item d-flex justify-content-between align-items-center";
                    li.innerHTML = `
                        <span>
                            <span class="d-inline-block rounded-circle me-2" style="width:12px;height:12px;background:${cat.color}"></span>
                            ${cat.nombre}
                        </span>
                        <button class="btn btn-sm btn-outline-danger" data-id="${cat.id_categoria}">
                            <i class="bx bx-trash"></i>
                        </button>
                    `;
                    li.querySelector("button").addEventListener("click", () => self.eliminarCategoria(cat.id_categoria));
                    self.listaCategorias.appendChild(li);
                });
            });
    }

    eliminarCategoria(id) {
        if (!confirm("¿Eliminar esta categoría? Las tareas que la usan se quedarán sin categoría.")) return;

        fetch(`/categorias/eliminar/${id}`, { method: 'POST' })
            .then(res => res.json())
            .then(data => {
                if (data.exito) {
                    this.cargarListaCategorias();
                    this.recargarDropdownCategorias();
                    this.calendarObj.refetchEvents();
                } else {
                    alert(data.mensaje);
                }
            });
    }

    recargarDropdownCategorias() {
        fetch('/categorias/index')
            .then(res => res.json())
            .then(data => {
                if (!data.exito) return;
                const valorActual = this.selectCategoria.value;
                this.selectCategoria.innerHTML = '<option value="">Sin categoría</option>';
                data.datos.forEach(cat => {
                    const opt = document.createElement("option");
                    opt.value = cat.id_categoria;
                    opt.textContent = cat.nombre;
                    this.selectCategoria.appendChild(opt);
                });
                const nuevaOpt = document.createElement("option");
                nuevaOpt.value = "__nueva__";
                nuevaOpt.textContent = "+ Nueva categoría";
                this.selectCategoria.appendChild(nuevaOpt);
                this.selectCategoria.value = valorActual;
            });
    }

    cargarCategorias() {
        fetch('/categorias/index')
            .then(res => res.json())
            .then(data => {
                if (data.exito) this.renderizarCategorias(data.datos);
            })
            .catch(err => console.error('Error al cargar categorías:', err));
    }

    renderizarCategorias(categorias) {
        // Lista dentro del modal "Mis categorías"
        const lista = document.getElementById('lista-categorias');
        lista.innerHTML = '';
        categorias.forEach(cat => {
            const li = document.createElement('li');
            li.className = 'list-group-item d-flex justify-content-between align-items-center';
            li.innerHTML = `
                <span><span class="badge me-2" style="background-color:${cat.color}">&nbsp;</span>${cat.nombre}</span>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="tareasCalendarInstance.eliminarCategoria(${cat.id_categoria})">Eliminar</button>
            `;
            lista.appendChild(li);
        });

        // Select del modal de tarea
        const select = document.getElementById('tarea-categoria');
        const valorActual = select.value;
        select.querySelectorAll('option:not([value=""]):not([value="__nueva__"])').forEach(opt => opt.remove());
        const nuevaOption = select.querySelector('option[value="__nueva__"]');
        categorias.forEach(cat => {
            const opt = document.createElement('option');
            opt.value = cat.id_categoria;
            opt.textContent = cat.nombre;
            select.insertBefore(opt, nuevaOption);
        });
        select.value = valorActual;
    }

    agregarCategoria(nombre, color, callback) {
        if (!nombre.trim()) {
            alert('Escribe un nombre para la categoría');
            return;
        }

        fetch('/categorias/agregar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ nombre, color })
        })
            .then(res => res.json())
            .then(data => {
                if (data.exito) {
                    this.cargarCategorias();
                    if (callback) callback(data.datos);
                } else {
                    alert(data.mensaje);
                }
            })
            .catch(err => console.error('Error al agregar categoría:', err));
    }

    eliminarCategoria(idCategoria) {
        if (!confirm('¿Eliminar esta categoría? Las tareas que la usan quedarán sin categoría.')) return;

        fetch(`/categorias/eliminar/${idCategoria}`, { method: 'POST' })
            .then(res => res.json())
            .then(data => {
                if (data.exito) this.cargarCategorias();
                else alert(data.mensaje);
            })
            .catch(err => console.error('Error al eliminar categoría:', err));
    }

    init() {
        const self = this;

        this.calendarObj = new FullCalendar.Calendar(this.calendarEl, {
            themeSystem: "bootstrap",
            initialView: "dayGridMonth",
            handleWindowResize: true,
            height: window.innerHeight - 200,
            headerToolbar: {
                left: "prev,next today",
                center: "title",
                right: "dayGridMonth,timeGridWeek,timeGridDay,listMonth"
            },
            buttonText: {
                today: "Hoy", month: "Mes", week: "Semana",
                day: "Día", list: "Lista", prev: "Anterior", next: "Siguiente"
            },
            events: (info, success, failure) => this.cargarEventos(info, success, failure),
            editable: false,
            selectable: true,

            dateClick: function (e) {
                self.abrirModalNuevo(e.dateStr);
            },

            eventClick: function (e) {
                self.abrirModalEditar(e.event);
            }
        });

        this.calendarObj.render();

        this.btnNew.addEventListener("click", () => this.abrirModalNuevo(null));
        this.btnDelete.addEventListener("click", () => this.eliminarTarea());

        this.form.addEventListener("submit", function (e) {
            e.preventDefault();
            if (self.form.checkValidity()) {
                self.guardarTarea();
            } else {
                e.stopPropagation();
                self.form.classList.add("was-validated");
            }
        });

        this.initCategorias();

        this.cargarCategorias();

        // Botón "+" del modal "Mis categorías"
        document.getElementById('btn-agregar-categoria').addEventListener('click', () => {
            const nombre = document.getElementById('cat-modal-nombre').value;
            const color = document.getElementById('cat-modal-color').value;
            this.agregarCategoria(nombre, color, () => {
                document.getElementById('cat-modal-nombre').value = '';
            });
        });

        // Select del modal de tarea: mostrar mini-formulario al elegir "+ Nueva categoría"
        const selectCategoria = document.getElementById('tarea-categoria');
        selectCategoria.addEventListener('change', function () {
            const inline = document.getElementById('nueva-categoria-inline');
            inline.classList.toggle('d-none', this.value !== '__nueva__');
        });

        // Botón "✓" del mini-formulario inline
        document.getElementById('btn-guardar-cat-inline').addEventListener('click', () => {
            const nombre = document.getElementById('nueva-cat-nombre').value;
            const color = document.getElementById('nueva-cat-color').value;
            this.agregarCategoria(nombre, color, (nuevaCat) => {
                document.getElementById('nueva-categoria-inline').classList.add('d-none');
                document.getElementById('nueva-cat-nombre').value = '';
                selectCategoria.value = nuevaCat.id_categoria;
            });
        });
    }
}

document.addEventListener("DOMContentLoaded", function () {
    (new TareasCalendar()).init();
});