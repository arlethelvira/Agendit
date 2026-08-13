class CalendarSchedule {
    constructor() {
        this.body = document.body;
        this.modal = new bootstrap.Modal(
            document.getElementById("event-modal"),
            { backdrop: "static" }
        );

        this.calendar = document.getElementById("calendar");
        this.formEvent = document.getElementById("forms-event");
        this.btnNewEvent = document.getElementById("btn-new-event");
        this.btnDeleteEvent = document.getElementById("btn-delete-event");
        this.btnSaveEvent = document.getElementById("btn-save-event");
        this.modalTitle = document.getElementById("modal-title");

        // Checkbox de "Marcar como hecho"
        this.wrapperCompletado = document.getElementById("wrapper-completado");
        this.checkCompletado = document.getElementById("event-completado");
        this.spanCompletadoFecha = document.getElementById("event-completado-fecha");

        this.calendarObj = null;
        this.selectedEvent = null;
        this.newEventData = null;

        /*
         * Cache local de los hábitos que vienen
         * del backend (HabitosController::index).
         * Se usa para expandir la recurrencia
         * sin tener que pedirlos otra vez cada
         * vez que cambia la vista del calendario.
         */
        this.habitos = [];

        /*
         * Set con claves "idHabito-YYYY-MM-DD" de
         * las ocurrencias ya marcadas como completadas.
         * Viene de /habitos/registros-completados.
         */
        this.registrosCompletados = new Set();
    }

    /*
     * Lee el token CSRF que pone CakePHP en la
     * cookie "csrfToken" (CsrfProtectionMiddleware).
     * Se manda en cada POST/PUT/DELETE.
     */
    getCsrfToken() {
        const match = document.cookie.match(/csrfToken=([^;]+)/);
        return match ? decodeURIComponent(match[1]) : null;
    }

    fetchJson(url, options = {}) {
        const token = this.getCsrfToken();

        const headers = Object.assign(
            {
                "Accept": "application/json",
                "Content-Type": "application/json"
            },
            token ? { "X-CSRF-Token": token } : {},
            options.headers || {}
        );

        return fetch(url, Object.assign({}, options, { headers }))
            .then(function (res) {
                return res.json().then(function (body) {
                    if (!res.ok) {
                        throw body;
                    }
                    return body;
                });
            });
    }

    /*
     * Trae los hábitos del usuario en sesión.
     */
    cargarHabitos() {
        const a = this;

        return this.fetchJson("/habitos/index")
            .then(function (res) {
                a.habitos = (res && res.data) ? res.data : [];
            })
            .catch(function (err) {
                console.error(
                    "No se pudieron cargar los hábitos:",
                    err
                );

                a.habitos = [];
            });
    }

    /*
     * Trae las fechas ya marcadas como completadas
     * y arma un Set para consultarlas rápido.
     */
    cargarRegistrosCompletados() {
        const a = this;

        return this.fetchJson("/habitos/registros-completados")
            .then(function (res) {
                const data = (res && res.data) ? res.data : [];

                a.registrosCompletados = new Set(
                    data.map(function (r) {
                        return r.id_habito + "-" + r.fecha;
                    })
                );
            })
            .catch(function (err) {
                console.error(
                    "No se pudieron cargar los registros de cumplimiento:",
                    err
                );

                a.registrosCompletados = new Set();
            });
    }

    /*
     * Expande un hábito en ocurrencias de calendario
     * dentro del rango [rangeStart, rangeEnd) según
     * su frecuencia, anclado a fecha_creacion.
     */
    expandirHabito(habito, rangeStart, rangeEnd) {
        const a = this;
        const eventos = [];

        const creado = new Date(habito.fecha_creacion);
        const inicio = new Date(
            creado.getFullYear(),
            creado.getMonth(),
            creado.getDate()
        );

        const frecuencia = (habito.frecuencia || "").toLowerCase();

        const pasos = {
            "diaria": 1,
            "cada 2 dias": 2,
            "cada 2 días": 2,
            "cada 3 dias": 3,
            "cada 3 días": 3,
            "semanal": 7
        };

        const pushOcurrencia = function (fecha) {
            const y = fecha.getFullYear();
            const m = String(fecha.getMonth() + 1).padStart(2, "0");
            const d = String(fecha.getDate()).padStart(2, "0");
            const fechaStr = y + "-" + m + "-" + d;

            const completado = a.registrosCompletados.has(
                habito.id_habito + "-" + fechaStr
            );

            eventos.push({
                id: habito.id_habito + "-" + y + m + d,
                title: (completado ? "✓ " : "") + habito.titulo,
                start: fechaStr,
                allDay: true,
                className: completado
                    ? habito.color + " habito-completado"
                    : habito.color,
                extendedProps: {
                    id_habito: habito.id_habito,
                    notas: habito.notas,
                    frecuencia: habito.frecuencia,
                    color: habito.color,
                    creado_por: habito.creado_por,
                    completado: completado
                }
            });
        };

        if (frecuencia === "mensual") {

            const cursor = new Date(inicio);

            while (cursor < rangeEnd) {
                if (cursor >= rangeStart && cursor >= inicio) {
                    pushOcurrencia(cursor);
                }
                cursor.setMonth(cursor.getMonth() + 1);
            }

            return eventos;
        }

        const paso = pasos[frecuencia] || 1;
        const cursor = new Date(inicio);

        while (cursor < rangeEnd) {
            if (cursor >= rangeStart) {
                pushOcurrencia(cursor);
            }
            cursor.setDate(cursor.getDate() + paso);
        }

        return eventos;
    }

    /*
     * Genera todas las ocurrencias de todos
     * los hábitos dentro del rango visible
     * del calendario.
     */
    generarEventos(rangeStart, rangeEnd) {
        const a = this;
        let eventos = [];

        this.habitos.forEach(function (habito) {
            eventos = eventos.concat(
                a.expandirHabito(habito, rangeStart, rangeEnd)
            );
        });

        return eventos;
    }

    /*
     * Refresca hábitos + registros de cumplimiento
     * y vuelve a pintar el calendario.
     */
    refrescarCalendario() {
        const a = this;

        return Promise.all([
            this.cargarHabitos(),
            this.cargarRegistrosCompletados()
        ]).then(function () {
            a.calendarObj.refetchEvents();
        });
    }

    onEventClick(e) {
        this.formEvent?.reset();
        this.formEvent.classList.remove("was-validated");

        this.newEventData = null;

        this.selectedEvent = e.event;

        const props = e.event.extendedProps;

        document.getElementById("event-title").value =
            e.event.title.replace(/^✓\s*/, "");

        document.getElementById("event-notas").value =
            props.notas || "";

        document.getElementById("event-frecuencia").value =
            props.frecuencia || "";

        document.getElementById("event-category").value =
            props.color;

        /*
         * Si el hábito fue asignado por un
         * especialista, el socio solo puede
         * verlo, no modificarlo.
         */
        const esSoloLectura = props.creado_por === "ESPECIALISTA";

        document.getElementById("event-title").disabled = esSoloLectura;
        document.getElementById("event-notas").disabled = esSoloLectura;
        document.getElementById("event-frecuencia").disabled = esSoloLectura;
        document.getElementById("event-category").disabled = esSoloLectura;

        this.btnSaveEvent.style.display = esSoloLectura ? "none" : "block";
        this.btnDeleteEvent.style.display = esSoloLectura ? "none" : "block";

        this.modalTitle.textContent = esSoloLectura
            ? "Hábito asignado por tu especialista (solo lectura)"
            : "Editar hábito";

        /*
         * Checkbox de "Marcar como hecho".
         *
         * Se muestra siempre (tanto para hábitos propios
         * como asignados por especialista, ya que el socio
         * SÍ puede marcar cumplimiento aunque no pueda
         * editar/eliminar un hábito asignado).
         */
        this.wrapperCompletado.style.display = "block";
        this.checkCompletado.checked = props.completado === true;
        this.spanCompletadoFecha.textContent = e.event.startStr;

        this.modal.show();
    }

    onSelect(e) {
        this.formEvent?.reset();
        this.formEvent?.classList.remove("was-validated");

        document.getElementById("event-title").disabled = false;
        document.getElementById("event-notas").disabled = false;
        document.getElementById("event-frecuencia").disabled = false;
        document.getElementById("event-category").disabled = false;

        this.selectedEvent = null;
        this.newEventData = e;

        this.btnSaveEvent.style.display = "block";
        this.btnDeleteEvent.style.display = "none";

        // No aplica marcar cumplimiento en un hábito que aún no existe.
        this.wrapperCompletado.style.display = "none";

        this.modalTitle.textContent = "Nuevo hábito";
        this.modal.show();

        this.calendarObj.unselect();
    }

    init() {

        const a = this;
        /* =========================================================
           FRECUENCIA POR DÍAS ESPECÍFICOS
        ========================================================= */

        const selectFrecuencia =
            document.getElementById("event-frecuencia");

        const contenedorDias =
            document.getElementById("contenedor-dias-especificos");

        const checkDias =
            document.querySelectorAll(".dia-habito");

        const errorDias =
            document.getElementById("error-dias-habito");


        /*
         * Mostrar u ocultar los días
         * según la frecuencia seleccionada.
         */
        selectFrecuencia.addEventListener(
            "change",
            function () {

                if (
                    selectFrecuencia.value ===
                    "dias_especificos"
                ) {

                    contenedorDias.style.display =
                        "block";

                } else {

                    contenedorDias.style.display =
                        "none";

                    errorDias.style.display =
                        "none";


                    /*
                     * Quitamos selecciones anteriores.
                     */
                    checkDias.forEach(function (check) {

                        check.checked = false;

                    });

                }

            }
        );

        /*
         * Cargamos los hábitos y sus registros de
         * cumplimiento antes de inicializar el calendario.
         */
        Promise.all([
            this.cargarHabitos(),
            this.cargarRegistrosCompletados()
        ]).then(function () {

            a.calendarObj = new FullCalendar.Calendar(
                a.calendar,
                {
                    plugins: [],

                    slotDuration: "00:30:00",
                    slotMinTime: "07:00:00",
                    slotMaxTime: "19:00:00",

                    themeSystem: "bootstrap",
                    bootstrapFontAwesome: false,

                    buttonText: {
                        today: "Hoy",
                        month: "Mes",
                        week: "Semana",
                        day: "Día",
                        list: "Lista",
                        prev: "Anterior",
                        next: "Siguiente"
                    },

                    initialView: "dayGridMonth",

                    handleWindowResize: true,

                    height: window.innerHeight - 200,

                    headerToolbar: {
                        left: "prev,next today",
                        center: "title",
                        right: "dayGridMonth,timeGridWeek,timeGridDay,listMonth"
                    },

                    /*
                     * En vez de una lista fija, generamos
                     * las ocurrencias según el rango que
                     * FullCalendar está mostrando.
                     */
                    events: function (fetchInfo, successCallback) {
                        successCallback(
                            a.generarEventos(
                                fetchInfo.start,
                                fetchInfo.end
                            )
                        );
                    },

                    editable: false,
                    droppable: false,
                    selectable: true,

                    dateClick: function (e) {
                        a.onSelect(e);
                    },

                    eventClick: function (e) {
                        a.onEventClick(e);
                    }
                }
            );

            /*
             * Mostrar calendario
             */
            a.calendarObj.render();
        });


        /*
         * Botón Nuevo Hábito
         */
        a.btnNewEvent.addEventListener("click", function () {
            a.onSelect({
                date: new Date(),
                allDay: true
            });
        });


        /*
         * Checkbox: marcar/desmarcar cumplimiento
         */
        a.checkCompletado.addEventListener("change", function () {

            if (!a.selectedEvent) {
                return;
            }

            const idHabito = a.selectedEvent.extendedProps.id_habito;
            const fecha = a.selectedEvent.startStr;

            // Deshabilitamos mientras se guarda, para evitar doble clic.
            a.checkCompletado.disabled = true;

            a.fetchJson("/habitos/marcar-completado/" + idHabito, {
                method: "POST",
                body: JSON.stringify({ fecha: fecha })
            })
                .then(function (res) {
                    a.checkCompletado.checked = !!res.completado;
                    return a.refrescarCalendario();
                })
                .catch(function (err) {
                    console.error("Error al marcar cumplimiento:", err);
                    alert("No se pudo actualizar el cumplimiento.");
                    // Revertimos el check visualmente si falló.
                    a.checkCompletado.checked = !a.checkCompletado.checked;
                })
                .finally(function () {
                    a.checkCompletado.disabled = false;
                });
        });


        /*
         * Guardar / editar hábito
         */
        a.formEvent?.addEventListener("submit", function (e) {

            e.preventDefault();

            const form = a.formEvent;

            if (!form.checkValidity()) {
                e.stopPropagation();
                form.classList.add("was-validated");
                return;
            }

            /* =========================================================
               OBTENER FRECUENCIA
            ========================================================= */

            let frecuenciaFinal =
                selectFrecuencia.value;


            /*
             * Si seleccionó días específicos,
             * obtenemos únicamente los checks marcados.
             */
            if (
                selectFrecuencia.value ===
                "dias_especificos"
            ) {

                const diasSeleccionados = [];

                checkDias.forEach(function (check) {

                    if (check.checked) {

                        diasSeleccionados.push(
                            check.value
                        );

                    }

                });


                /*
                 * Debe seleccionar mínimo un día.
                 */
                if (
                    diasSeleccionados.length === 0
                ) {

                    errorDias.style.display =
                        "block";

                    return;

                }


                errorDias.style.display =
                    "none";


                /*
                 * Ejemplo:
                 *
                 * lunes,miercoles,viernes
                 */
                frecuenciaFinal =
                    diasSeleccionados.join(",");

            }


            /* =========================================================
               PAYLOAD
            ========================================================= */

            const payload = {

                titulo:
                    document
                        .getElementById("event-title")
                        .value,

                notas:
                    document
                        .getElementById("event-notas")
                        .value,

                frecuencia:
                    frecuenciaFinal,

                color:
                    document
                        .getElementById("event-category")
                        .value

            };

            /*
             * Editar hábito existente
             */
            if (a.selectedEvent) {

                const idHabito = a.selectedEvent.extendedProps.id_habito;

                a.fetchJson("/habitos/edit/" + idHabito, {
                    method: "PUT",
                    body: JSON.stringify(payload)
                })
                    .then(function () {
                        a.modal.hide();
                        return a.refrescarCalendario();
                    })
                    .catch(function (err) {
                        console.error("Error al editar hábito:", err);
                        alert("No se pudo guardar el hábito.");
                    });

            }

            /*
             * Crear nuevo hábito
             */
            else {

                a.fetchJson("/habitos/add", {
                    method: "POST",
                    body: JSON.stringify(payload)
                })
                    .then(function () {
                        a.modal.hide();
                        return a.refrescarCalendario();
                    })
                    .catch(function (err) {
                        console.error("Error al crear hábito:", err);
                        alert("No se pudo crear el hábito.");
                    });
            }
        });


        /*
         * Eliminar hábito
         */
        a.btnDeleteEvent.addEventListener("click", function () {

            if (!a.selectedEvent) {
                return;
            }

            const idHabito = a.selectedEvent.extendedProps.id_habito;

            a.fetchJson("/habitos/delete/" + idHabito, {
                method: "DELETE"
            })
                .then(function () {
                    a.selectedEvent = null;
                    a.modal.hide();
                    return a.refrescarCalendario();
                })
                .catch(function (err) {
                    console.error("Error al eliminar hábito:", err);
                    alert("No se pudo eliminar el hábito.");
                });
        });
    }
}


/*
 * Inicializar calendario cuando cargue la página
 */
document.addEventListener("DOMContentLoaded", function () {

    (new CalendarSchedule()).init();

});