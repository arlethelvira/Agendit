class TareasCalendar {
    constructor() {
        this.modal = new bootstrap.Modal(document.getElementById("tarea-modal"), { backdrop: "static" });
        this.calendarEl = document.getElementById("calendar-tareas");
        this.form = document.getElementById("form-tarea-calendario");
        this.btnNew = document.getElementById("btn-new-tarea");
        this.btnDelete = document.getElementById("btn-delete-tarea");
        this.modalTitle = document.getElementById("tarea-modal-title");
        this.calendarObj = null;
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
        this.btnDelete.style.display = "none";
        this.modalTitle.textContent = "Nueva tarea";
        this.modal.show();
    }

    abrirModalEditar(eventInfo) {
        this.form.reset();
        this.form.classList.remove("was-validated");

        const props = eventInfo.extendedProps;
        document.getElementById("tarea-id").value = eventInfo.id;
        document.getElementById("tarea-titulo").value = eventInfo.title;
        document.getElementById("tarea-notas").value = props.notas || '';

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
    }
}

document.addEventListener("DOMContentLoaded", function () {
    (new TareasCalendar()).init();
});