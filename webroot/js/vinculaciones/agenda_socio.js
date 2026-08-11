document.addEventListener("DOMContentLoaded", function () {
    const calendarEl = document.getElementById("calendar-agenda-socio");
    const idSocio = calendarEl.dataset.idSocio;

    const modal = new bootstrap.Modal(document.getElementById("tarea-modal"), { backdrop: "static" });
    const form = document.getElementById("form-asignar-tarea");
    const btnNew = document.getElementById("btn-new-tarea");
    const btnEliminar = document.getElementById("btn-eliminar-tarea");
    const btnGuardar = document.getElementById("btn-guardar-tarea");
    const modalTitle = document.querySelector("#tarea-modal .modal-title");

    let editingTareaId = null;

    function abrirModoCrear() {
        editingTareaId = null;
        form.reset();
        form.classList.remove("was-validated");
        btnEliminar.classList.add("d-none");
        btnGuardar.textContent = "Asignar";
        modalTitle.textContent = "Asignar tarea";
    }

    function abrirModoEditar(evento) {
        editingTareaId = evento.id;
        form.reset();
        form.classList.remove("was-validated");

        document.getElementById("tarea-titulo").value = evento.title;
        document.getElementById("tarea-notas").value = evento.extendedProps.notas || "";

        const start = evento.start;
        const yyyy = start.getFullYear();
        const mm = String(start.getMonth() + 1).padStart(2, "0");
        const dd = String(start.getDate()).padStart(2, "0");
        document.getElementById("tarea-fecha").value = `${yyyy}-${mm}-${dd}`;

        if (!evento.allDay) {
            const hh = String(start.getHours()).padStart(2, "0");
            const min = String(start.getMinutes()).padStart(2, "0");
            document.getElementById("tarea-hora").value = `${hh}:${min}`;
        }

        btnEliminar.classList.remove("d-none");
        btnGuardar.textContent = "Guardar cambios";
        modalTitle.textContent = "Editar tarea asignada";
    }

    const calendar = new FullCalendar.Calendar(calendarEl, {
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
        events: function (info, success, failure) {
            fetch(`/tareas/eventos-socio/${idSocio}`)
                .then(res => res.json())
                .then(data => {
                    if (data.exito) success(data.datos);
                    else failure();
                })
                .catch(() => failure());
        },
        editable: false,
        selectable: true,

        dateClick: function (e) {
            abrirModoCrear();
            document.getElementById("tarea-fecha").value = e.dateStr;
            modal.show();
        },

        eventClick: function (e) {
            // Si no es editable (es "Ocupado", tarea propia del socio), no abrimos nada
            if (!e.event.extendedProps.editable) return;

            abrirModoEditar(e.event);
            modal.show();
        }
    });

    calendar.render();

    btnNew.addEventListener("click", function () {
        abrirModoCrear();
        modal.show();
    });

    btnEliminar.addEventListener("click", function () {
        if (!editingTareaId) return;
        if (!confirm("¿Seguro que quieres eliminar esta tarea?")) return;

        fetch(`/tareas/eliminar-asignada/${editingTareaId}`, { method: 'POST' })
            .then(res => res.json())
            .then(data => {
                if (data.exito) {
                    modal.hide();
                    calendar.refetchEvents();
                } else {
                    alert(data.mensaje);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Ocurrió un error al eliminar la tarea.');
            });
    });

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        if (!form.checkValidity()) {
            e.stopPropagation();
            form.classList.add("was-validated");
            return;
        }

        const payload = {
            id_usuario: idSocio,
            titulo: document.getElementById("tarea-titulo").value,
            fecha_limite: document.getElementById("tarea-fecha").value,
            hora_limite: document.getElementById("tarea-hora").value,
            notas: document.getElementById("tarea-notas").value,
        };

        const url = editingTareaId
            ? `/tareas/editar-asignada/${editingTareaId}`
            : '/tareas/asignar';

        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
            .then(res => res.json())
            .then(data => {
                if (data.exito) {
                    modal.hide();
                    calendar.refetchEvents();
                } else {
                    alert(data.mensaje);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Ocurrió un error al guardar la tarea.');
            });
    });
});