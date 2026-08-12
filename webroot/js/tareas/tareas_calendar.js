class TareasCalendar {

    constructor() {

        /* =====================================================
           MODALES Y CALENDARIO
        ===================================================== */

        this.modal = new bootstrap.Modal(
            document.getElementById("tarea-modal"),
            {
                backdrop: "static"
            }
        );

        this.calendarEl =
            document.getElementById("calendar-tareas");

        this.form =
            document.getElementById("form-tarea-calendario");

        this.btnNew =
            document.getElementById("btn-new-tarea");

        this.btnDelete =
            document.getElementById("btn-delete-tarea");

        this.modalTitle =
            document.getElementById("tarea-modal-title");

        this.calendarObj = null;


        /* =====================================================
           CONTROL PARA EVITAR DUPLICADOS
        ===================================================== */

        /*
         * Evita que el formulario pueda enviarse
         * varias veces mientras el primer POST
         * todavía está procesándose.
         */
        this.guardandoTarea = false;

        this.guardandoCategoria = false;


        /* =====================================================
           CATEGORÍAS - MODAL DE TAREA
        ===================================================== */

        this.selectCategoria =
            document.getElementById("tarea-categoria");

        this.inlineForm =
            document.getElementById("nueva-categoria-inline");

        this.inputNombre =
            document.getElementById("nueva-cat-nombre");

        this.inputColor =
            document.getElementById("nueva-cat-color");

        this.btnGuardarCat =
            document.getElementById("btn-guardar-cat-inline");


        /* =====================================================
           MODAL MIS CATEGORÍAS
        ===================================================== */

        this.listaCategorias =
            document.getElementById("lista-categorias");

        this.catModalNombre =
            document.getElementById("cat-modal-nombre");

        this.catModalColor =
            document.getElementById("cat-modal-color");

        this.btnAgregarCategoria =
            document.getElementById("btn-agregar-categoria");

        this.categoriasModalEl =
            document.getElementById("categorias-modal");
    }


    /* =========================================================
       CSRF CAKEPHP
    ========================================================= */

    getCsrfToken() {

        const match =
            document.cookie.match(
                /csrfToken=([^;]+)/
            );

        return match
            ? decodeURIComponent(match[1])
            : null;
    }


    /* =========================================================
       FETCH JSON
    ========================================================= */

    fetchJson(url, options = {}) {

        const token =
            this.getCsrfToken();

        const headers = {
            "Accept": "application/json",
            "Content-Type": "application/json",

            ...(token
                ? {
                    "X-CSRF-Token": token
                }
                : {}),

            ...(options.headers || {})
        };


        return fetch(
            url,
            {
                ...options,
                headers
            }
        )
        .then(async response => {

            const texto =
                await response.text();

            let data;

            try {

                data = texto
                    ? JSON.parse(texto)
                    : {};

            } catch (error) {

                console.error(
                    "El servidor no devolvió JSON:",
                    texto
                );

                throw new Error(
                    "Respuesta inválida del servidor."
                );
            }


            if (!response.ok) {

                throw data;

            }


            return data;
        });
    }


    /* =========================================================
       MENSAJES BONITOS
    ========================================================= */

    mostrarMensaje(
        mensaje,
        tipo = "success"
    ) {

        let clase =
            "alert-success";

        let icono =
            "ti-circle-check";


        if (tipo === "error") {

            clase =
                "alert-danger";

            icono =
                "ti-circle-x";
        }


        if (tipo === "warning") {

            clase =
                "alert-warning";

            icono =
                "ti-alert-triangle";
        }


        const alerta =
            document.createElement("div");


        alerta.className = `
            alert
            ${clase}
            shadow
            position-fixed
            fade
            show
        `;


        alerta.style.top =
            "85px";

        alerta.style.right =
            "25px";

        alerta.style.zIndex =
            "12000";

        alerta.style.minWidth =
            "300px";

        alerta.style.maxWidth =
            "420px";


        alerta.innerHTML = `

            <div
                class="
                    d-flex
                    align-items-center
                    gap-2
                "
            >

                <i
                    class="
                        ti
                        ${icono}
                        fs-20
                    "
                ></i>

                <div class="flex-grow-1">
                    ${this.escaparHtml(mensaje)}
                </div>

                <button
                    type="button"
                    class="btn-close"
                ></button>

            </div>
        `;


        alerta
            .querySelector(".btn-close")
            .addEventListener(
                "click",
                () => alerta.remove()
            );


        document.body.appendChild(
            alerta
        );


        setTimeout(
            () => {

                if (alerta.parentElement) {

                    alerta.remove();

                }

            },
            3500
        );
    }


    escaparHtml(texto) {

        const div =
            document.createElement("div");

        div.textContent =
            texto ?? "";

        return div.innerHTML;
    }


    /* =========================================================
       CARGAR EVENTOS
    ========================================================= */

    cargarEventos(
        fetchInfo,
        successCallback,
        failureCallback
    ) {

        this.fetchJson(
            "/tareas/eventos"
        )
        .then(data => {

            if (data.exito) {

                /*
                 * FullCalendar reemplaza los eventos
                 * de esta fuente al ejecutar refetchEvents().
                 *
                 * NO usamos addEvent() manualmente.
                 */
                successCallback(
                    data.datos || []
                );

            } else {

                console.error(
                    "Error al cargar eventos:",
                    data.mensaje
                );

                failureCallback();
            }

        })
        .catch(error => {

            console.error(
                "Error al cargar tareas:",
                error
            );

            failureCallback();
        });
    }


    /* =========================================================
       NUEVA TAREA
    ========================================================= */

    abrirModalNuevo(
        fechaSeleccionada = null
    ) {

        this.form.reset();

        this.form.classList.remove(
            "was-validated"
        );


        document
            .getElementById("tarea-id")
            .value = "";


        document
            .getElementById("tarea-fecha")
            .value =
                fechaSeleccionada || "";


        document
            .getElementById("tarea-hora")
            .value = "";


        document
            .getElementById("tarea-notas")
            .value = "";


        this.inlineForm.classList.add(
            "d-none"
        );


        /*
         * Si previamente seleccionó
         * Nueva categoría, regresamos
         * el select a un valor válido.
         */
        if (
            this.selectCategoria.value ===
            "__nueva__"
        ) {

            this.selectCategoria.value =
                "";
        }


        this.btnDelete.style.display =
            "none";


        this.modalTitle.textContent =
            "Nueva tarea";


        this.modal.show();
    }


    /* =========================================================
       EDITAR TAREA
    ========================================================= */

    abrirModalEditar(
        eventInfo
    ) {

        this.form.reset();

        this.form.classList.remove(
            "was-validated"
        );


        this.inlineForm.classList.add(
            "d-none"
        );


        const props =
            eventInfo.extendedProps;


        document
            .getElementById("tarea-id")
            .value =
                eventInfo.id;


        document
            .getElementById("tarea-titulo")
            .value =
                eventInfo.title || "";


        document
            .getElementById("tarea-notas")
            .value =
                props.notas || "";


        document
            .getElementById("tarea-categoria")
            .value =
                props.idCategoria || "";


        document
            .getElementById(
                "nueva-categoria-inline"
            )
            .classList.add(
                "d-none"
            );


        /*
         * Limpiamos la hora primero,
         * por si anteriormente se editó
         * una tarea con hora.
         */
        document
            .getElementById("tarea-hora")
            .value = "";


        const start =
            eventInfo.startStr;


        if (start) {

            document
                .getElementById("tarea-fecha")
                .value =
                    start.substring(0, 10);


            if (
                start.includes("T")
            ) {

                document
                    .getElementById("tarea-hora")
                    .value =
                        start.substring(
                            11,
                            16
                        );
            }
        }


        /*
         * Una tarea del especialista
         * no se puede editar/eliminar
         * desde el calendario personal.
         */
        const asignadaEspecialista =
            props.asignadaPorEspecialista === true;


        document
            .getElementById("tarea-titulo")
            .disabled =
                asignadaEspecialista;


        document
            .getElementById("tarea-notas")
            .disabled =
                asignadaEspecialista;


        document
            .getElementById("tarea-fecha")
            .disabled =
                asignadaEspecialista;


        document
            .getElementById("tarea-hora")
            .disabled =
                asignadaEspecialista;


        document
            .getElementById("tarea-categoria")
            .disabled =
                asignadaEspecialista;


        const botonGuardar =
            this.form.querySelector(
                'button[type="submit"]'
            );


        if (botonGuardar) {

            botonGuardar.style.display =
                asignadaEspecialista
                    ? "none"
                    : "";
        }


        this.btnDelete.style.display =
            asignadaEspecialista
                ? "none"
                : "block";


        this.modalTitle.textContent =
            asignadaEspecialista
                ? "Tarea asignada por tu especialista"
                : "Editar tarea";


        this.modal.show();
    }


    /* =========================================================
       RESTAURAR CAMPOS DEL MODAL
    ========================================================= */

    habilitarFormulario() {

        [
            "tarea-titulo",
            "tarea-notas",
            "tarea-fecha",
            "tarea-hora",
            "tarea-categoria"
        ]
        .forEach(id => {

            const elemento =
                document.getElementById(id);

            if (elemento) {

                elemento.disabled =
                    false;
            }
        });


        const botonGuardar =
            this.form.querySelector(
                'button[type="submit"]'
            );


        if (botonGuardar) {

            botonGuardar.style.display =
                "";
        }
    }


    /* =========================================================
       GUARDAR TAREA
    ========================================================= */

    guardarTarea() {

        /*
         * Protección principal contra
         * múltiples POST.
         */
        if (this.guardandoTarea) {

            return;
        }


        const idTarea =
            document
                .getElementById("tarea-id")
                .value;


        const idCategoria =
            document
                .getElementById(
                    "tarea-categoria"
                )
                .value;


        /*
         * __nueva__ nunca debe llegar
         * al backend como categoría.
         */
        if (
            idCategoria === "__nueva__"
        ) {

            this.mostrarMensaje(
                "Primero guarda la nueva categoría.",
                "warning"
            );

            return;
        }


        const url =
            idTarea
                ? `/tareas/editar/${idTarea}`
                : "/tareas/agregar";


        const payload = {

            titulo:
                document
                    .getElementById(
                        "tarea-titulo"
                    )
                    .value
                    .trim(),

            fecha_limite:
                document
                    .getElementById(
                        "tarea-fecha"
                    )
                    .value,

            hora_limite:
                document
                    .getElementById(
                        "tarea-hora"
                    )
                    .value,

            id_categoria:
                idCategoria,

            notas:
                document
                    .getElementById(
                        "tarea-notas"
                    )
                    .value
                    .trim(),

            /*
             * Este calendario no maneja
             * subtareas por ahora.
             */
            subtareas: []
        };


        if (!payload.titulo) {

            this.mostrarMensaje(
                "El título es obligatorio.",
                "warning"
            );

            return;
        }


        const botonGuardar =
            this.form.querySelector(
                'button[type="submit"]'
            );


        const textoAnterior =
            botonGuardar
                ? botonGuardar.innerHTML
                : "";


        this.guardandoTarea =
            true;


        if (botonGuardar) {

            botonGuardar.disabled =
                true;


            botonGuardar.innerHTML = `

                <span
                    class="
                        spinner-border
                        spinner-border-sm
                        me-1
                    "
                ></span>

                Guardando...
            `;
        }


        this.fetchJson(
            url,
            {
                method: "POST",

                body:
                    JSON.stringify(
                        payload
                    )
            }
        )
        .then(data => {

            if (!data.exito) {

                throw data;
            }


            /*
             * PRIMERO cerramos el modal.
             */
            this.modal.hide();


            /*
             * Limpiamos el formulario.
             */
            this.form.reset();


            /*
             * Refetch reemplaza la fuente
             * actual de eventos.
             */
            this.calendarObj.refetchEvents();


            this.mostrarMensaje(
                idTarea
                    ? "Tarea actualizada correctamente."
                    : "Tarea creada correctamente.",
                "success"
            );

        })
        .catch(error => {

            console.error(
                "Error al guardar tarea:",
                error
            );


            this.mostrarMensaje(
                error.mensaje ||
                "No fue posible guardar la tarea.",
                "error"
            );

        })
        .finally(() => {

            this.guardandoTarea =
                false;


            if (botonGuardar) {

                botonGuardar.disabled =
                    false;


                botonGuardar.innerHTML =
                    textoAnterior;
            }
        });
    }


    /* =========================================================
       ELIMINAR TAREA
    ========================================================= */

    eliminarTarea() {

        const idTarea =
            document
                .getElementById("tarea-id")
                .value;


        if (!idTarea) {

            return;
        }


        /*
         * Por ahora conservamos confirm.
         *
         * Si quieres luego reutilizamos
         * el modal bonito de /tareas.
         */
        if (
            !window.confirm(
                "¿Eliminar esta tarea?"
            )
        ) {

            return;
        }


        this.fetchJson(
            `/tareas/eliminar/${idTarea}`,
            {
                method: "POST"
            }
        )
        .then(data => {

            if (!data.exito) {

                throw data;
            }


            this.modal.hide();

            this.calendarObj.refetchEvents();


            this.mostrarMensaje(
                "Tarea eliminada correctamente.",
                "success"
            );

        })
        .catch(error => {

            console.error(
                "Error al eliminar tarea:",
                error
            );


            this.mostrarMensaje(
                error.mensaje ||
                "No fue posible eliminar la tarea.",
                "error"
            );
        });
    }


    /* =========================================================
       CATEGORÍAS
    ========================================================= */

    initCategorias() {

        const self = this;


        /* -----------------------------------------------------
           SELECT "+ NUEVA CATEGORÍA"
        ----------------------------------------------------- */

        this.selectCategoria
            .addEventListener(
                "change",
                function () {

                    if (
                        this.value ===
                        "__nueva__"
                    ) {

                        self.inlineForm
                            .classList
                            .remove(
                                "d-none"
                            );


                        self.inputNombre
                            .focus();

                    } else {

                        self.inlineForm
                            .classList
                            .add(
                                "d-none"
                            );
                    }
                }
            );


        /* -----------------------------------------------------
           CREAR CATEGORÍA INLINE
        ----------------------------------------------------- */

        this.btnGuardarCat
            .addEventListener(
                "click",
                () => {

                    const nombre =
                        this.inputNombre
                            .value
                            .trim();


                    const color =
                        this.inputColor
                            .value;


                    if (!nombre) {

                        this.inputNombre.focus();

                        return;
                    }


                    this.crearCategoria(
                        nombre,
                        color,
                        (nuevaCategoria) => {

                            this.inlineForm
                                .classList
                                .add(
                                    "d-none"
                                );


                            this.inputNombre
                                .value = "";


                            /*
                             * Recargamos el dropdown
                             * y dejamos seleccionada
                             * la nueva categoría.
                             */
                            this.recargarDropdownCategorias(
                                nuevaCategoria.id_categoria
                            );
                        }
                    );
                }
            );


        /* -----------------------------------------------------
           AL ABRIR MODAL "MIS CATEGORÍAS"
        ----------------------------------------------------- */

        this.categoriasModalEl
            .addEventListener(
                "show.bs.modal",
                () => {

                    this.cargarListaCategorias();

                }
            );


        /* -----------------------------------------------------
           CREAR CATEGORÍA DESDE MODAL
        ----------------------------------------------------- */

        this.btnAgregarCategoria
            .addEventListener(
                "click",
                () => {

                    const nombre =
                        this.catModalNombre
                            .value
                            .trim();


                    const color =
                        this.catModalColor
                            .value;


                    if (!nombre) {

                        this.catModalNombre
                            .focus();

                        return;
                    }


                    this.crearCategoria(
                        nombre,
                        color,
                        () => {

                            this.catModalNombre
                                .value = "";


                            this.cargarListaCategorias();

                            this.recargarDropdownCategorias();
                        }
                    );
                }
            );
    }


    /* =========================================================
       CREAR CATEGORÍA
    ========================================================= */

    crearCategoria(
        nombre,
        color,
        callback = null
    ) {

        /*
         * Evita doble clic / doble POST.
         */
        if (this.guardandoCategoria) {

            return;
        }


        this.guardandoCategoria =
            true;


        this.fetchJson(
            "/categorias/agregar",
            {
                method: "POST",

                body:
                    JSON.stringify({
                        nombre,
                        color
                    })
            }
        )
        .then(data => {

            if (!data.exito) {

                throw data;
            }


            this.mostrarMensaje(
                "Categoría creada correctamente.",
                "success"
            );


            if (callback) {

                callback(
                    data.datos
                );
            }

        })
        .catch(error => {

            console.error(
                "Error al crear categoría:",
                error
            );


            this.mostrarMensaje(
                error.mensaje ||
                "No fue posible crear la categoría.",
                "error"
            );

        })
        .finally(() => {

            this.guardandoCategoria =
                false;

        });
    }


    /* =========================================================
       CARGAR LISTA DE CATEGORÍAS
    ========================================================= */

    cargarListaCategorias() {

        this.fetchJson(
            "/categorias/index"
        )
        .then(data => {

            this.listaCategorias
                .innerHTML = "";


            if (
                !data.exito ||
                !data.datos ||
                data.datos.length === 0
            ) {

                this.listaCategorias
                    .innerHTML = `

                        <li
                            class="
                                list-group-item
                                text-muted
                            "
                        >
                            Aún no tienes categorías
                        </li>
                    `;

                return;
            }


            data.datos.forEach(
                categoria => {

                    const li =
                        document.createElement(
                            "li"
                        );


                    li.className = `
                        list-group-item
                        d-flex
                        justify-content-between
                        align-items-center
                    `;


                    li.innerHTML = `

                        <span>

                            <span
                                class="
                                    d-inline-block
                                    rounded-circle
                                    me-2
                                "

                                style="
                                    width:12px;
                                    height:12px;
                                    background:
                                    ${categoria.color};
                                "
                            ></span>

                            ${this.escaparHtml(
                                categoria.nombre
                            )}

                        </span>


                        <button
                            type="button"
                            class="
                                btn
                                btn-sm
                                btn-outline-danger
                            "
                        >

                            <i class="bx bx-trash"></i>

                        </button>
                    `;


                    li
                        .querySelector("button")
                        .addEventListener(
                            "click",
                            () => {

                                this.eliminarCategoria(
                                    categoria.id_categoria
                                );

                            }
                        );


                    this.listaCategorias
                        .appendChild(li);
                }
            );
        })
        .catch(error => {

            console.error(
                "Error al cargar categorías:",
                error
            );
        });
    }


    /* =========================================================
       RECARGAR SELECT DE CATEGORÍAS
    ========================================================= */

    recargarDropdownCategorias(
        seleccionarId = null
    ) {

        this.fetchJson(
            "/categorias/index"
        )
        .then(data => {

            if (!data.exito) {

                return;
            }


            const valorActual =
                seleccionarId ??
                this.selectCategoria.value;


            this.selectCategoria
                .innerHTML = `

                    <option value="">
                        Sin categoría
                    </option>
                `;


            data.datos.forEach(
                categoria => {

                    const option =
                        document.createElement(
                            "option"
                        );


                    option.value =
                        categoria.id_categoria;


                    option.textContent =
                        categoria.nombre;


                    this.selectCategoria
                        .appendChild(
                            option
                        );
                }
            );


            const opcionNueva =
                document.createElement(
                    "option"
                );


            opcionNueva.value =
                "__nueva__";


            opcionNueva.textContent =
                "+ Nueva categoría";


            this.selectCategoria
                .appendChild(
                    opcionNueva
                );


            if (
                valorActual &&
                valorActual !== "__nueva__"
            ) {

                this.selectCategoria.value =
                    String(valorActual);

            } else {

                this.selectCategoria.value =
                    "";
            }
        });
    }


    /* =========================================================
       ELIMINAR CATEGORÍA
    ========================================================= */

    eliminarCategoria(
        idCategoria
    ) {

        if (
            !window.confirm(
                "¿Eliminar esta categoría? Las tareas que la usan quedarán sin categoría."
            )
        ) {

            return;
        }


        this.fetchJson(
            `/categorias/eliminar/${idCategoria}`,
            {
                method: "POST"
            }
        )
        .then(data => {

            if (!data.exito) {

                throw data;
            }


            this.cargarListaCategorias();

            this.recargarDropdownCategorias();

            this.calendarObj.refetchEvents();


            this.mostrarMensaje(
                "Categoría eliminada correctamente.",
                "success"
            );

        })
        .catch(error => {

            console.error(
                "Error al eliminar categoría:",
                error
            );


            this.mostrarMensaje(
                error.mensaje ||
                "No fue posible eliminar la categoría.",
                "error"
            );
        });
    }


    /* =========================================================
       INICIALIZAR
    ========================================================= */

    init() {

        const self =
            this;


        /* =====================================================
           FULLCALENDAR
        ===================================================== */

        this.calendarObj =
            new FullCalendar.Calendar(
                this.calendarEl,
                {

                    themeSystem:
                        "bootstrap",

                    initialView:
                        "dayGridMonth",

                    handleWindowResize:
                        true,

                    height:
                        window.innerHeight - 200,


                    headerToolbar: {

                        left:
                            "prev,next today",

                        center:
                            "title",

                        right:
                            "dayGridMonth,timeGridWeek,timeGridDay,listMonth"
                    },


                    buttonText: {

                        today:
                            "Hoy",

                        month:
                            "Mes",

                        week:
                            "Semana",

                        day:
                            "Día",

                        list:
                            "Lista",

                        prev:
                            "Anterior",

                        next:
                            "Siguiente"
                    },


                    /*
                     * Una sola fuente de eventos.
                     */
                    events:
                        (
                            info,
                            success,
                            failure
                        ) => {

                            this.cargarEventos(
                                info,
                                success,
                                failure
                            );
                        },


                    editable:
                        false,

                    selectable:
                        true,


                    dateClick:
                        function (e) {

                            /*
                             * Nos aseguramos de restaurar
                             * los campos por si antes abrió
                             * una tarea del especialista.
                             */
                            self.habilitarFormulario();

                            self.abrirModalNuevo(
                                e.dateStr
                            );
                        },


                    eventClick:
                        function (e) {

                            self.habilitarFormulario();

                            self.abrirModalEditar(
                                e.event
                            );
                        }
                }
            );


        this.calendarObj.render();


        /* =====================================================
           NUEVA TAREA
        ===================================================== */

        this.btnNew
            .addEventListener(
                "click",
                () => {

                    this.habilitarFormulario();

                    this.abrirModalNuevo(
                        null
                    );
                }
            );


        /* =====================================================
           ELIMINAR
        ===================================================== */

        this.btnDelete
            .addEventListener(
                "click",
                () => {

                    this.eliminarTarea();

                }
            );


        /* =====================================================
           SUBMIT
        ===================================================== */

        this.form
            .addEventListener(
                "submit",
                (e) => {

                    e.preventDefault();


                    /*
                     * Si ya hay un envío en curso,
                     * bloqueamos completamente
                     * cualquier segundo submit.
                     */
                    if (
                        this.guardandoTarea
                    ) {

                        return;
                    }


                    if (
                        this.form.checkValidity()
                    ) {

                        this.guardarTarea();

                    } else {

                        e.stopPropagation();

                        this.form
                            .classList
                            .add(
                                "was-validated"
                            );
                    }
                }
            );


        /* =====================================================
           CATEGORÍAS
        ===================================================== */

        /*
         * IMPORTANTE:
         *
         * Solo inicializamos categorías UNA VEZ.
         *
         * En tu código anterior había listeners
         * duplicados aquí y dentro de
         * initCategorias().
         */
        this.initCategorias();

        this.recargarDropdownCategorias();
    }
}


/* =========================================================
   INICIALIZACIÓN ÚNICA
========================================================= */

document.addEventListener(
    "DOMContentLoaded",
    function () {

        /*
         * Si por accidente el archivo JS
         * llega a cargarse dos veces en la página,
         * evitamos crear dos instancias del
         * calendario y dos listeners de submit.
         */
        if (
            window.__tareasCalendarInicializado
        ) {

            console.warn(
                "TareasCalendar ya estaba inicializado."
            );

            return;
        }


        window.__tareasCalendarInicializado =
            true;


        window.tareasCalendarInstance =
            new TareasCalendar();


        window.tareasCalendarInstance
            .init();
    }
);