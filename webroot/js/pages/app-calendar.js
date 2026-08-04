class CalendarSchedule {
    constructor() {
        this.body = document.body;
        this.modal = new bootstrap.Modal(document.getElementById("event-modal"), { backdrop: "static" });
        this.calendar = document.getElementById("calendar");
        this.formEvent = document.getElementById("forms-event");
        this.btnNewEvent = document.getElementById("btn-new-event");
        this.btnDeleteEvent = document.getElementById("btn-delete-event");
        this.btnSaveEvent = document.getElementById("btn-save-event");
        this.modalTitle = document.getElementById("modal-title");
        this.filtroIdUsuario = document.getElementById("filtro-id-usuario");
        this.calendarObj = null;
        this.selectedHabitoId = null;
        this.selectedDate = null;
    }

    idUsuarioActual() {
        return this.filtroIdUsuario.value || 4;
    }

    formatFecha(d) {
        const y = d.getFullYear();
        const m = String(d.getMonth() + 1).padStart(2, "0");
        const day = String(d.getDate()).padStart(2, "0");
        return `${y}-${m}-${day}`;
    }

    // Genera las fechas (YYYY-MM-DD) en que debe aparecer un hábito
    // dentro del rango [rangeStart, rangeEnd), según su frecuencia.
    generarFechas(habito, rangeStart, rangeEnd) {
        const baseStr = (habito.fecha_creacion || "").substring(0, 10);
        if (!baseStr) return [];

        const base = new Date(baseStr + "T00:00:00");
        const start = new Date(rangeStart);
        start.setHours(0, 0, 0, 0);
        const end = new Date(rangeEnd);
        end.setHours(0, 0, 0, 0);

        const frecuencia = (habito.frecuencia || "").trim().toLowerCase();
        const fechas = [];

        const pasos = { "diaria": 1, "cada 2 dias": 2, "cada 3 dias": 3, "semanal": 7 };

        if (frecuencia === "mensual") {
            let cursor = new Date(base);
            while (cursor < start) cursor.setMonth(cursor.getMonth() + 1);
            while (cursor < end) {
                if (cursor >= base) fechas.push(this.formatFecha(cursor));
                cursor.setMonth(cursor.getMonth() + 1);
            }
            return fechas;
        }

        if (pasos[frecuencia]) {
            const step = pasos[frecuencia];
            let cursor = new Date(base);
            if (cursor < start) {
                const diffDias = Math.floor((start - cursor) / 86400000);
                const saltos = Math.ceil(diffDias / step);
                cursor.setDate(cursor.getDate() + saltos * step);
            }
            while (cursor < end) {
                if (cursor >= base) fechas.push(this.formatFecha(cursor));
                cursor.setDate(cursor.getDate() + step);
            }
            return fechas;
        }

        // Frecuencia no reconocida: se muestra una sola vez en su fecha de creación
        if (base >= start && base < end) fechas.push(this.formatFecha(base));
        return fechas;
    }

    async fetchHabitosRaw() {
        const idUsuario = this.idUsuarioActual();
        const res = await fetch(`/habitos?id_usuario=${idUsuario}`);
        const resultado = await res.json();
        if (!resultado.ok) {
            console.error("Error al cargar hábitos:", resultado.error);
            return [];
        }
        return resultado.data;
    }

    abrirModalNuevo(fecha) {
        this.formEvent?.reset();
        this.formEvent?.classList.remove("was-validated");
        this.selectedHabitoId = null;
        this.selectedDate = fecha;
        this.btnDeleteEvent.style.display = "none";
        this.modalTitle.textContent = "Nuevo Hábito";
        this.modal.show();
    }

    abrirModalEditar(event) {
        this.formEvent?.reset();
        this.formEvent?.classList.remove("was-validated");
        this.selectedHabitoId = event.id;
        this.selectedDate = null;
        this.btnDeleteEvent.style.display = "block";
        this.modalTitle.textContent = "Editar Hábito";

        document.getElementById("event-title").value = event.title;
        document.getElementById("event-notas").value = event.extendedProps.notas || "";
        document.getElementById("event-frecuencia").value = event.extendedProps.frecuencia || "";
        document.getElementById("event-category").value = event.classNames[0] || "bg-primary";

        this.modal.show();
    }

    async guardarHabito(e) {
        e.preventDefault();
        const form = this.formEvent;
        if (!form.checkValidity()) {
            e.stopPropagation();
            form.classList.add("was-validated");
            return;
        }

        const datos = {
            titulo: document.getElementById("event-title").value,
            notas: document.getElementById("event-notas").value,
            frecuencia: document.getElementById("event-frecuencia").value,
            color: document.getElementById("event-category").value,
        };

        let url;
        if (this.selectedHabitoId) {
            // Editar: NO se manda id_usuario, se conserva el dueño original
            url = `/habitos/edit/${this.selectedHabitoId}`;
        } else {
            // Crear: se asigna id_usuario y la fecha elegida, fija al mediodía
            // para evitar que un corrimiento de zona horaria cruce la medianoche.
            url = `/habitos/add`;
            datos.id_usuario = this.idUsuarioActual();
            datos.fecha_creacion = `${this.selectedDate}T12:00:00`;
        }

        const res = await fetch(url, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(datos),
        });
        const resultado = await res.json();

        if (resultado.ok) {
            this.modal.hide();
            this.calendarObj.refetchEvents();
        } else {
            alert("Error al guardar: " + JSON.stringify(resultado.error));
        }
    }

    async eliminarHabito() {
        if (!this.selectedHabitoId) return;
        if (!confirm("¿Eliminar este hábito? Esto lo quita de todas sus fechas repetidas.")) return;

        const res = await fetch(`/habitos/delete/${this.selectedHabitoId}`, { method: "POST" });
        const resultado = await res.json();

        if (resultado.ok) {
            this.modal.hide();
            this.calendarObj.refetchEvents();
        } else {
            alert("Error al eliminar: " + JSON.stringify(resultado.error));
        }
    }

    init() {
        const self = this;

        self.calendarObj = new FullCalendar.Calendar(self.calendar, {
            plugins: [],
            themeSystem: "bootstrap",
            bootstrapFontAwesome: false,
            buttonText: {
                today: "Hoy", month: "Mes", week: "Semana", day: "Día",
                list: "Lista", prev: "Ant", next: "Sig",
            },
            initialView: "dayGridMonth",
            handleWindowResize: true,
            height: window.innerHeight - 200,
            headerToolbar: {
                left: "prev,next today",
                center: "title",
                right: "dayGridMonth,timeGridWeek,timeGridDay,listMonth",
            },
            events: function (fetchInfo, successCallback, failureCallback) {
                self.fetchHabitosRaw().then(habitos => {
                    const eventos = [];
                    habitos.forEach(h => {
                        const fechas = self.generarFechas(h, fetchInfo.start, fetchInfo.end);
                        fechas.forEach(f => {
                            eventos.push({
                                id: h.id_habito,
                                title: h.titulo,
                                start: f,
                                allDay: true,
                                className: h.color || "bg-primary",
                                extendedProps: {
                                    notas: h.notas,
                                    frecuencia: h.frecuencia,
                                    id_usuario: h.id_usuario,
                                },
                            });
                        });
                    });
                    successCallback(eventos);
                }).catch(failureCallback);
            },
            editable: false,
            selectable: true,
            dateClick: function (info) {
                self.abrirModalNuevo(info.dateStr);
            },
            eventClick: function (info) {
                self.abrirModalEditar(info.event);
            },
        });

        self.calendarObj.render();

        self.btnNewEvent.addEventListener("click", function () {
            const hoy = self.formatFecha(new Date());
            self.abrirModalNuevo(hoy);
        });

        self.formEvent?.addEventListener("submit", function (e) {
            self.guardarHabito(e);
        });

        self.btnDeleteEvent.addEventListener("click", function () {
            self.eliminarHabito();
        });

        self.filtroIdUsuario.addEventListener("change", function () {
            self.calendarObj.refetchEvents();
        });
    }
}

document.addEventListener("DOMContentLoaded", function () {
    new CalendarSchedule().init();
});