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

        this.calendarObj = null;
        this.selectedEvent = null;
        this.newEventData = null;
    }

    onEventClick(e) {
        this.formEvent?.reset();
        this.formEvent.classList.remove("was-validated");

        this.newEventData = null;
        this.btnDeleteEvent.style.display = "block";

        this.modalTitle.textContent = "Editar hábito";
        this.modal.show();

        this.selectedEvent = e.event;

        document.getElementById("event-title").value =
            this.selectedEvent.title;

        document.getElementById("event-category").value =
            this.selectedEvent.classNames[0];
    }

    onSelect(e) {
        this.formEvent?.reset();
        this.formEvent?.classList.remove("was-validated");

        this.selectedEvent = null;
        this.newEventData = e;

        this.btnDeleteEvent.style.display = "none";

        this.modalTitle.textContent = "Nuevo hábito";
        this.modal.show();

        this.calendarObj.unselect();
    }

    init() {

        const e = new Date();
        const a = this;

        /*
         * Eventos de prueba.
         *
         * Se eliminó FullCalendar.Draggable porque
         * la vista de Agendit no tiene #external-events.
         */
        const t = [
            {
                title: "Interview - Backend Engineer",
                start: e,
                end: e,
                className: "bg-primary"
            },
            {
                title: "Meeting with CT Team",
                start: new Date(Date.now() + 13e6),
                end: e,
                className: "bg-warning"
            },
            {
                title: "Meeting with Mr. Reback",
                start: new Date(Date.now() + 308e6),
                end: new Date(Date.now() + 338e6),
                className: "bg-info"
            },
            {
                title: "Interview - Frontend Engineer",
                start: new Date(Date.now() + 6057e4),
                end: new Date(Date.now() + 153e6),
                className: "bg-secondary"
            },
            {
                title: "Phone Screen - Frontend Engineer",
                start: new Date(Date.now() + 168e6),
                className: "bg-success"
            },
            {
                title: "Buy Design Assets",
                start: new Date(Date.now() + 33e7),
                end: new Date(Date.now() + 3308e5),
                className: "bg-primary"
            },
            {
                title: "Setup Github Repository",
                start: new Date(Date.now() + 1008e6),
                end: new Date(Date.now() + 1108e6),
                className: "bg-danger"
            },
            {
                title: "Meeting with Mr. Shreyu",
                start: new Date(Date.now() + 2508e6),
                end: new Date(Date.now() + 2508e6),
                className: "bg-dark"
            }
        ];

        /*
         * Inicializar FullCalendar
         */
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

                initialEvents: t,

                editable: true,
                droppable: true,
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
         * Guardar / editar hábito
         */
        a.formEvent?.addEventListener("submit", function (e) {

            e.preventDefault();

            const form = a.formEvent;

            if (form.checkValidity()) {

                /*
                 * Editar evento existente
                 */
                if (a.selectedEvent) {

                    a.selectedEvent.setProp(
                        "title",
                        document.getElementById("event-title").value
                    );

                    a.selectedEvent.setProp(
                        "classNames",
                        document.getElementById("event-category").value
                    );

                }

                /*
                 * Crear nuevo evento
                 */
                else {

                    const nuevoEvento = {
                        title: document.getElementById("event-title").value,

                        start: a.newEventData.date,

                        allDay: a.newEventData.allDay,

                        className:
                            document.getElementById("event-category").value
                    };

                    a.calendarObj.addEvent(nuevoEvento);
                }

                a.modal.hide();

            } else {

                e.stopPropagation();

                form.classList.add("was-validated");
            }
        });


        /*
         * Eliminar hábito
         */
        a.btnDeleteEvent.addEventListener("click", function () {

            if (a.selectedEvent) {

                a.selectedEvent.remove();

                a.selectedEvent = null;

                a.modal.hide();
            }
        });
    }
}


/*
 * Inicializar calendario cuando cargue la página
 */
document.addEventListener("DOMContentLoaded", function () {

    (new CalendarSchedule()).init();

});